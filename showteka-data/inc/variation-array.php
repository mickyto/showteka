<?php
function sub_variation($offer, $seat) {

  return $variation = array(
    'attributes' => array(
      'sector'  => (string) $offer->SectorId,
      'row'     => (string) $offer->Row,
      'place'   => (string) $seat,
    ),
    'price'     => (string) $offer->AgentPrice,
    'date&time' => (string) $offer->EventDateTime
  );
}
?>
