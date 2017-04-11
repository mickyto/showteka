<?php
function process_sh_update_sectors() {

  $sectors = get_option( 'sectors' );
  $sector_ids = array_keys($sectors);

  foreach ($_POST['places'] as $place => $place_name) {
    $stage_array = sht_api_request('<PlaceId>'. $place .'</PlaceId>', 'GetStageListByPlaceId');
    foreach ($stage_array->ResponseData->ResponseDataObject->Stage as $stage) {

      $sector_array = sht_api_request('<StageId>'. (string)$stage->Id .'</StageId>', 'GetSectorListByStageId');

      foreach ($sector_array->ResponseData->ResponseDataObject->Sector as $sector) {
        if (!in_array($sector->Id, $sector_ids)) {
          $sectors[(string) $sector->Id] = (string) $sector->Name;
        }
      }
    }
  }

  update_option( 'sectors', $sectors );
  update_option( 'places', $_POST['places'] );
  wp_redirect( admin_url( 'admin.php?page=showteka_places' ) );

  exit;
}
?>
