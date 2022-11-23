<?php
class Paystack
{
    private
        $_skey = "sk_live_3b5c7898b33ee5e96b9f9192ffed8ddda76fcff8",
        $_pkey = "pk_live_8f524810ed918d6d02424e5eab4dc7ec3df21a4c",

        $_skey_test = "sk_test_98e5b5e7ea385c906377921e370476ad5a9d75eb",
        $_pkey_test = "pk_test_f81ed953d02a5d7c0f40c154748364980ca7e943",

        $_db,
        $_data,
        $_table = 'categories';

    function __construct()
    {
        $this->_db = DB::getInstance();
        $this->_curl = curl_init();
    }

    function listBanks()
    {
        // $this->_curl = curl_init();
        curl_setopt_array($this->_curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer {$this->_skey}",
                "Cache-Control: no-cache",
            ),

        ));

        $response = curl_exec($this->_curl);
        $err = curl_error($this->_curl);
        curl_close($this->_curl);
        return json_decode($response);
    }
    
    function resolveAccountNumber($account_number = '0001234567', $bank_code = '058')
    {
        $this->_curl = curl_init();
  
        curl_setopt_array($this->_curl, array(
            CURLOPT_URL => "https://api.paystack.co/bank/resolve?account_number={$account_number}&bank_code={$bank_code}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
              "Authorization: Bearer {$this->_skey}",
              "Cache-Control: no-cache",
            ),
        ));
        
        $response = curl_exec($this->_curl);
        $err = curl_error($this->_curl);
        
        curl_close($this->_curl);
        return json_decode($response);
    }

    function createSubaccount($business_name = "Sunshine Studios", $settlement_bank = "011", $account_number = "0000000000", $email = "test@mail.com", $name = "Test User", $phone = "08000000000", $percentage_charge = 3)
    {
        $url = "https://api.paystack.co/subaccount";
        $fields = [
            'business_name' => $business_name,
            'settlement_bank' => $settlement_bank,
            'account_number' => $account_number,
            'percentage_charge' => $percentage_charge,
            'primary_contact_email' => $email,
            'primary_contact_name' => $name,
            'primary_contact_phone' => $phone,
        ];

        $fields_string = http_build_query($fields);
        //open connection
        $this->_curl = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($this->_curl, CURLOPT_URL, $url);
        curl_setopt($this->_curl, CURLOPT_POST, true);
        curl_setopt($this->_curl, CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->_skey}",
            "Cache-Control: no-cache",
        ));

        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($this->_curl, CURLOPT_RETURNTRANSFER, true);
        //execute post
        $result = curl_exec($this->_curl);
        return json_decode($result);
    }
    
    function createTransferRecipient($account_name = "Sunshine Studios", $account_number = "0000000000", $bank_code = "011", $type = "nuban", $currency = "NGN")
    {
        $url = "https://api.paystack.co/transferrecipient";
        $fields = [
            'type' => $type,
            'name' => $account_name,
            'account_number' => $account_number,
            'bank_code' => $bank_code,
            'currency' => $currency
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $this->_curl = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($this->_curl,CURLOPT_URL, $url);
        curl_setopt($this->_curl,CURLOPT_POST, true);
        curl_setopt($this->_curl,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer {$this->_skey}",
        "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($this->_curl,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($this->_curl);
        return json_decode($result);
    }
    
    function initiateTransfer($recipient_code = "RCP_t0ya41mp35flk40", $amount= "3794800", $reason = "Holiday Flexing", $source = "balance")
    {
        $url = "https://api.paystack.co/transfer";
        $fields = [
            'source' => $source,
            'amount' => $amount,
            'recipient' => $recipient_code,
            'reason' => $recipient_code
        ];
        $fields_string = http_build_query($fields);
        //open connection
        $this->_curl = curl_init();
        
        //set the url, number of POST vars, POST data
        curl_setopt($this->_curl,CURLOPT_URL, $url);
        curl_setopt($this->_curl,CURLOPT_POST, true);
        curl_setopt($this->_curl,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->_skey_test}",
            "Cache-Control: no-cache",
        ));
        
        //So that curl_exec returns the contents of the cURL; rather than echoing it
        curl_setopt($this->_curl,CURLOPT_RETURNTRANSFER, true); 
        
        //execute post
        $result = curl_exec($this->_curl);
        return json_decode($result);
    }
    
    function bulkTransfers($transfers)
    {
        $url = "https://api.paystack.co/transfer/bulk";
          $fields = [
            'currency' => "NGN",
            'source' => "balance",
            'transfers' => $transfers
          ];
          $fields_string = http_build_query($fields);
          //open connection
          $this->_curl = curl_init();
          
          //set the url, number of POST vars, POST data
          curl_setopt($this->_curl,CURLOPT_URL, $url);
          curl_setopt($this->_curl,CURLOPT_POST, true);
          curl_setopt($this->_curl,CURLOPT_POSTFIELDS, $fields_string);
          curl_setopt($this->_curl, CURLOPT_HTTPHEADER, array(
            "Authorization: Bearer {$this->_skey_test}",
            "Cache-Control: no-cache",
          ));
          
          //So that curl_exec returns the contents of the cURL; rather than echoing it
          curl_setopt($this->_curl,CURLOPT_RETURNTRANSFER, true); 
          
          //execute post
          $result = curl_exec($this->_curl);
          json_decode($result);
    }
    
    
}
