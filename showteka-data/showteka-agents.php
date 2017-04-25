<?php
function api_agents_plugin_settings_page() {

	$agents = get_option( 'api_agents' );
	$agent_array = sht_api_request('', 'GetAgentList');
	?>
	<div class="wrap">
		<div class="postbox-container">
			<h1>Агенты</h1>
			<?php
			if (gettype($agent_array) == 'object') {
				?>
				<form method="post" action="options.php">
					<?php
					settings_fields( 'api-agents-plugin-settings-group' );
					do_settings_sections( 'api-agents-plugin-settings-group' );
					?>
					<table cellpadding="10" border = "1">
						<thead>
							<tr>
								<th></th>
								<th>Имя</th>
								<th>Название компании</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$api_agents = array();
							foreach ($agent_array->ResponseData->ResponseDataObject->Agent as $agent) {
								$api_agents[] = (string)$agent->Id;
								?>
								<tr>
									<td class="stage">
										<input type="checkbox" name="api_agents[]" value="<?php echo $agent->Id; ?>" <?php checked( in_array( $agent->Id, $agents ) ); ?> />
									</td>
									<td class="stage"><p><?php echo $agent->Name; ?> | id: <?php echo $agent->Id; ?></p></td>
									<td class="stage"><p><?php echo $agent->CompanyName; ?></p></td>
								</tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<?php submit_button(); ?>
				</form>
				<?php
			}

			if (gettype($agent_array) == 'string') {
				?>
				<div id='message' class='error notice'><p><strong>Похоже мы не можем получить ответ от сервера. Проверьте работу API.</strong></p>
					<p>Текст ошибки:</p><small><?php echo $agent_array; ?></small>
				</div>
				<?php
			}

			$removed = array_diff($agents, $api_agents);
			if (count($removed)) {
				$agents = array_diff($agents, $removed);
				update_option( 'api_agents', $agents );
			}
			?>
		</div>
	</div>
	<?php
}
?>
