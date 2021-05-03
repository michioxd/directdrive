<?php
function get_file($id)
    {
        $__url = filter_var(strip_tags($_POST['load_url']), FILTER_SANITIZE_STRING);
        $_url = str_replace("https://drive.google.com/file/d/", "", $__url);
        $iurl = str_replace("/view?usp=sharing", "", $_url);
        $iiurl = str_replace("/view?usp=drivesdk", "", $iurl);
        $iiiurl = str_replace("/view", "", $iiurl);
        $iiiiurl = str_replace("/preview", "", $iiiurl);
        $idd = $iiiiurl;
        $start = curl_init("https://drive.google.com/uc?id=".$idd."&authuser=0&export=download");
        curl_setopt_array($start, array(
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_HTTPHEADER => [
                'accept-encoding: gzip, deflate, br',
                'content-length: 0',
                'content-type: application/x-www-form-urlencoded;charset=UTF-8',
                'origin: https://drive.google.com',
                'user-agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 11_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/90.0.4430.93 Safari/537.36',
                'x-client-data: CIS2yQEIorbJAQjBtskBCKmdygEIlqzKAQj4x8oBCNHhygEI5JzLAQipncsBCOidywEIoKDLAQjf78sB', // Replace with your client-data
                'x-drive-first-party: DriveWebUi',
                'x-json-requested: true'
            ],
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_POSTFIELDS => [],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false
        ));
        $res = curl_exec($start);
        $res_http = curl_getinfo($start, CURLINFO_HTTP_CODE);
        curl_close($start);
        if ($res_http == '200') {
            $json_data = json_decode(str_replace(')]}\'', '', $res));
            if (isset($json_data->downloadUrl)) {
                return $json_data->downloadUrl;
            }
        } else {
            return $res_http;
        }
    }
