<?php
function sht_api_request($request_data_object, $action_name) {
  $xml = '<?xml version="1.0" encoding="UTF-8"?>
  <Request>
  <RequestAuthorization>
  <UserId>10277</UserId>
  <Hash>b5744d1e326e866aac01ac17bffb3e5b</Hash>
  </RequestAuthorization>
  <RequestData>
  <RequestDataObject>'. $request_data_object .'</RequestDataObject>
  </RequestData>
  </Request>';

  try {
    $client = new SoapClient('http://api.zriteli.ru/index.php?wsdl', array("trace" => 1, "exception" => 0));
    $response = $client->__soapCall($action_name, array($xml));
    $response_object = simplexml_load_string($response);
    if ($response_object->ResponseResult->Code == 1) return 'Неверный составлен запрос';
    if ($response_object->ResponseResult->Code == 2) return 'Ошибка авторизации. Скорее всего пора оплатить API';
    return $response_object;
  } catch (SoapFault $exception) {
    return $exception->getMessage();
  }
}
?>
