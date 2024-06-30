<?php

namespace App\Http\Controllers\payment;

use App\Enum\TransactionStatusRequestEnum;
use App\Http\Controllers\Controller;
use App\Mail\SendStatusPayment;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Number;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\Cache;

class FlowController extends Controller
{
    private $products = array();


    function cryptParameterS($queryParams) {

        $keys = array_keys($queryParams);
        sort($keys);

        $toSign = '';

        foreach ($keys as $key) {
            $toSign .= $key . $queryParams[$key];
        };
        if(!function_exists("hash_hmac")) {
			throw new Exception("function hash_hmac not exist", 1);
		}

       return hash_hmac('sha256', $toSign, env('FLOW_SECRET_KEY'));

    }

    public function create(Request $request) {
       

        //--------------------------
        $uuid = (string) Uuid::uuid4();
        $content = $request->getContent(); 
        $numeroOrden = mt_rand(100000, 999999); // Puedes ajustar el rango según tus necesidades
        $total_cost = 0;


        // decodificamos el array
        $decodeContent = json_decode($content, true);

        //return $decodeContent['products'];
        

        Log::channel('flow_payment')->info('[FlowController].[Create] El Usuario '. $decodeContent['userData']['name'] . ' '. $decodeContent['userData']['surname'] . ' inicio una transacción');

       foreach ( $decodeContent['products'] as $dataProduct ) {    
            $this->products[] = $dataProduct; 
            $total_cost += $dataProduct['amount'] * $dataProduct['cantidad'];
        }

        Cache::put('products', $this->products);


        $queryParams = array(
                'amount' => $total_cost,
                'apiKey' => env('FLOW_API_KEY'),
                'commerceOrder' => $numeroOrden,
                'currency' => 'CLP',
                'email' => $decodeContent['userData']['email'],
                'paymentMethod' => 9,
                'payment_currency' => 'CLP',
                'subject' => 'El Usuario hizo una nueva compra ' .$decodeContent['userData']['name'] . ' '. $decodeContent['userData']['surname'] ,
                'urlConfirmation' => 'http://127.0.0.1:8000/api/v1/flow/confirmation',
                'urlReturn' => 'http://localhost:5173/payment/return'
            );

        $queryParams['s'] = $this->cryptParameterS($queryParams);
        
        try {

           // Puedes usar Http::asForm() para enviar datos como formulario
            $response = Http::asForm()->post(env('FLOW_URL_API') . '/payment/create', $queryParams);

            // Obtiene la respuesta como una cadena JSON
            $data = $response->json();

            Log::channel('flow_payment')->info('[FlowController].[Create] Se crea la orden de pago N° Orden Flow: '. json_encode($data['flowOrder']));
            Log::channel('flow_payment')->info('[FlowController].[Create] Inicia el proceso de guardar la transaccion en la base de datos ....');

            
            Log::channel('flow_payment')->info('[FlowController].[Create] El usuario... acaba de comprar ....');

            $user = \App\Models\User::create([
                'first_name' => $decodeContent['userData']['name'],
                'last_name' => $decodeContent['userData']['surname'],
                'email' => $decodeContent['userData']['email'],
                'phone' => $decodeContent['userData']['phone'],
                'address' => $decodeContent['userData']['address'],
                'city' => $decodeContent['userData']['city'],
                'postal_code' => $decodeContent['userData']['postalCode']
            ]);

            \App\Models\Transaction::create([
                'uuid' => $uuid,
                'id_owner' => $user->id,
                'commerceOrder' => $numeroOrden,
                'token' => $data['token'],
                'method_pay' => 9,
                'amount' => $total_cost,
            ]);

            return $data;
                        
        } catch (\Throwable $th) {
            return $th;
        }
    }

    public function confirmation(Request $request) {
        $token = $request->token;

        if(!$token) {
             // Si el token no se encuentra en el cuerpo, intenta obtenerlo de los parámetros de consulta
            $token = $request->query('token');

            if (!$token) {
                // Si el token aún está vacío, lanza una excepción o maneja el error según tus necesidades
               // throw new Exception('Token no válido.');
               return 'Token invalido';
            }
        }

        $data = $this->getStatus($token); // true convierte el objeto JSON en un array asociativo

        // Generamos la consulta
        $transactionStore = \App\Models\Transaction::where('token', $token)
                                                      ->where('status', TransactionStatusRequestEnum::PENDING)
                                                      ->first();

        if( $transactionStore ) {
            
            $products = Cache::get('products', []); // Obtener los productos de la caché o un array vacío si no existen

        
            // Agregamos datos al objeto data
            $data['productos'] = $products;
            $data['methodPay'] = $transactionStore->method_pay;
            $data['total_cost'] = Number::currency($transactionStore->amount, 'CLP');

            if($data['status'] !== 2) {            // Actualizamos el estado a rechazado en la base de datos
                Log::channel('flow_payment')->info('[FlowController].[Confirmation] El pago ha sido rechazado, por lo tanto se actualiza en la base de datos');
                $transactionStore->status = TransactionStatusRequestEnum::REJECTED;
                $transactionStore->save();

              //  $subject_mail = 'InvictusRP N° Orden: #'.$transactionStore->commerceOrder . ' Pago Fallido';

              //  $mail = new SendStatusPayment($subject_mail, $data);
              //  $mailSend = $mail->build();
              //  Mail::to($transactionStore->email)->send($mailSend, $subject_mail);
                
                return response()->json([ 'data' => $data, 'message' => 'Recuperado los datos exitosamente', 'status' => 'Pago Rechazado' ], 200);
            }

            // Si es aprobado 
            Log::channel('flow_payment')->info('[FlowController].[Confirmation] El pago ha sido aprobado, por lo tanto se actualiza en la base de datos');
            Log::channel('flow_payment')->info('[FlowController].[Confirmation] Se inicia el proceso de insercion de la compra');
            $transactionStore->status = TransactionStatusRequestEnum::APPROVED;
            $transactionStore->save();

            foreach($products as $product) {

                $category = \App\Models\Category::find($product['id_category']);

                $Variant = \App\Models\ProductVariant::find($product['selectedVariant']['id']);

                $variantJSON = json_encode([
                    'id_variant' => $product['selectedVariant']['id'],
                    'stock' => $product['cantidad'],
                    'talla' => $product['selectedSize']
                ]);

                \App\Models\Purchase::create([
                    'transaction_id' => $transactionStore->id,
                    'code_product' => $product['sku'],
                    'product_name' => $product['name'],
                    'product_category' => $category->name,
                    'product_variant' => $variantJSON, // Aquí el id como JSON
                ]);

                $variantData = json_decode($Variant->variant, true);
                $selectedSize = $product['selectedSize'];
                $cantidad = $product['cantidad'];

                foreach ($variantData as &$v) {
                    if ($v['size'] === $selectedSize) {
                        $v['stock'] -= $cantidad;
                    }
                }

                // Actualizar el JSON de la variante en la base de datos
                $Variant->variant = json_encode($variantData);
                $Variant->save();
            }

            $data['productos'] = $products;

          //  dd($data);
            
           // $subject_mail = 'InvictusRP N° Orden: #'.$transactionStore->commerceOrder . ' Pago Aprobado';
           // $mail = new SendStatusPayment($subject_mail, $data);
           // $mailSend = $mail->build();
           // Mail::to($transactionStore->email)->send($mailSend, $subject_mail);

            // Se procede a descontar .... Stock 
           // Helper::decrementStockProduct($token);

            return response()->json([ 'data' => $data, 'message' => 'Recuperado los datos exitosamente', 'status' => 'Pago Aprobado'], 200);
        }else {
            return response()->json([ 'message' => 'El token ingresado no existe' ], 400);
        }
    }

    public function getStatus($token) {


        $queryParams = array(
            'apiKey' => env('FLOW_API_KEY'),
            'token' => $token 
        );

        $queryParams['s'] = $this->cryptParameterS($queryParams);

        try {
             // Construir la cadena de consulta manualmente
            $queryString = http_build_query($queryParams);

            // Construir la URL con los parámetros de consulta
            $url = env('FLOW_URL_API') . '/payment/getStatus?' . $queryString;

            // Realiza la solicitud GET usando Http::get()
            $response = Http::get($url, $queryParams);

            // Obtiene la respuesta como una cadena JSON
            $data = $response->json();

            return $data;

        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
