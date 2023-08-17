<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class notificationWhatsapp extends Model
{
    protected $table = '';

    public static function whatsappMessage($telephone,$message){

        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => "https://api.wassenger.com/v1/messages",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => "{\"phone\":\"+212".$telephone."\",\"message\":\"".$message.".\"}",
          CURLOPT_HTTPHEADER => [
            "Content-Type: application/json",
            "Token: e1a08cd8ba83b0d45269629513ac97afa4d02c8d0d935ccf6938271acada551a55f7204d62d1f120"
          ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          return "cURL Error #:" . $err;
        } else {
            return $response;
        }
    }

}
