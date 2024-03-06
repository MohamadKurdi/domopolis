<?php echo $header; ?>
<style>
	h2,.box > .content h2 {font-size: 18px; clear: both; border-bottom: none; font-weight: 400}

	.tile-block{width:16%; float: left; margin-left:5px;}

	@media only screen and (max-width: 600px) {
		.tile-block{width:100%; float: left; margin-left:0px;}
	}

	.tile-body {overflow: hidden;}

	.tile.good > .tile-heading, .tile.good > .tile-footer{background-color: #00ad07; }
	.tile.good > .tile-body{background-color: #00ad07;opacity: 0.8;}

	.tile.warn > .tile-heading, .tile.warn > .tile-footer{background-color: #ff7815; }
	.tile.warn > .tile-body{background-color: #ff7815;opacity: 0.8;}

	.tile.bad > .tile-heading, .tile.bad > .tile-footer{background-color: #ce1400; }
	.tile.bad > .tile-body{background-color: #ce1400;opacity: 0.8;}

	.tile.unknown > .tile-heading, .tile.unknown > .tile-footer{background-color: #24a4c1; }
	.tile.unknown > .tile-body{background-color: #24a4c1;opacity: 0.8;}

	.tile-result{color:#FFF; font-size:28px; float:right; display:inline-block; max-width: 180px;max-height: 45px;}
	.tile-result.small{font-size: 14px;max-width: 180px;max-height: 45px;}
</style>

<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>

	<div class="box">   
		<div class="content">		
			<div style="clear:both"></div>			

				<h2>Балансы</h2>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getZadarmaBalance">
						<div class="tile-heading">Баланс Zadarma</div>
						<div class="tile-body"><i class="fa fa-phone"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							телефония
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getSMSBalance">
						<div class="tile-heading">Баланс SMS-шлюза</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							смс-шлюз <?php echo $this->config->get('config_smsgate_library');?>
						</div>
					</div>
				</div>

				<?php for ($i=1; $i<=$this->config->get('config_goip4_simnumber'); $i++) { ?>
					<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getGoip4BalanceUSSD" data-defer="<?php echo (int)(10000*$i); ?>" data-x="<?php echo $i; ?>">
						<div class="tile-heading">Баланс Goip4, <?php echo $i; ?></div>
						<div class="tile-body"><i class="fa fa-mobile"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							номер <?php echo $this->config->get('config_goip4_simnumber_' . $i); ?>
						</div>
					</div>
				</div>
				<?php } ?>


				<h2>Внешние API</h2>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getRainforestInfo" data-update-interval="600000">
						<div class="tile-heading">Rainforest API</div>
						<div class="tile-body"><i class="fa fa-amazon"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							получение цен и наличия
						</div>
					</div>
				</div>

			<?php if ($this->config->get('config_country_id') == 176) { ?>
				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getPricevaInfo&store_id=0" data-update-interval="10000">
						<div class="tile-heading">Priceva API RU Кампания</div>
						<div class="tile-body"><i class="fa fa-eur"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							цены конкурентов рф
						</div>
					</div>
				</div>
			<?php } ?>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getPricevaInfo&store_id=1" data-update-interval="10000">
						<div class="tile-heading">Priceva API UA Кампания</div>
						<div class="tile-body"><i class="fa fa-eur"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							цены конкурентов Украина
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getDadataInfo" data-update-interval="600000">
						<div class="tile-heading">DADATA API</div>
						<div class="tile-body"><i class="fa fa-search"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							кол-во запросов
						</div>
					</div>
				</div>

			<?php if ($this->config->get('config_country_id') == 176) { ?>
				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getYandexAPIInfo" data-update-interval="600000">
						<div class="tile-heading">YAM API</div>
						<div class="tile-body"><i class="fa fa-yahoo"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							yandex market api статус
						</div>
					</div>
				</div>
			<?php } ?>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getIpapiInfo" data-update-interval="600000">
						<div class="tile-heading">ip-api</div>
						<div class="tile-body"><i class="fa fa-globe"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							для подбора локации по ip
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getOpenAIInfo" data-update-interval="300000">
						<div class="tile-heading">🤖 OpenAI API</div>
						<div class="tile-body"><i class="fa fa-refresh"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							Всякие умные штуки
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getYandexTranslatorInfo" data-update-interval="600000">
						<div class="tile-heading">🤖 Yandex Translate API</div>
						<div class="tile-body"><i class="fa fa-refresh"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							автоматический перевод
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getAzureTranslatorInfo" data-update-interval="600000">
						<div class="tile-heading">🤖 Azure Translate API</div>
						<div class="tile-body"><i class="fa fa-refresh"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							автоматический перевод
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getDeeplTranslatorInfo" data-update-interval="600000">
						<div class="tile-heading">🤖 Deepl Translate API</div>
						<div class="tile-body"><i class="fa fa-refresh"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							автоматический перевод
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getMailGunInfo">
						<div class="tile-heading">MailGun</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							сервис отправки почты
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getSparkPostInfo">
						<div class="tile-heading">SparkPost</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							сервис отправки почты
						</div>
					</div>
				</div>

			<?php if ($this->config->get('config_country_id') == 176) { ?>
				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getCDEKInfo" data-update-interval="300000">
						<div class="tile-heading">CDEK</div>
						<div class="tile-body"><i class="fa fa-truck"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							сервис доставки
						</div>
					</div>
				</div>
			<?php } ?>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getNovaPoshtaInfo" data-update-interval="300000">
						<div class="tile-heading">NovaPoshta</div>
						<div class="tile-body"><i class="fa fa-truck"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							сервис доставки
						</div>
					</div>
				</div>			
			
				<h2>Наши сервисы</h2>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/pingAPI">
						<div class="tile-heading">OUR API TTFB</div>
						<div class="tile-body"><i class="fa fa-cog"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							<?php echo HTTPS_CATALOG . 'api/ping'; ?>
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getServerResponceTime">
						<div class="tile-heading">TTFB</div>
						<div class="tile-body"><i class="fa fa-rocket"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							<?php echo HTTPS_CATALOG; ?>
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getRedisInfo">
						<div class="tile-heading">Память Redis</div>
						<div class="tile-body"><i class="fa fa-cogs"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							движок кэширования
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getPageCacheInfo">
						<div class="tile-heading">Данные PageCache</div>
						<div class="tile-body"><i class="fa fa-cogs"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							объемы данных PageCache
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getAsteriskInfo">
						<div class="tile-heading">Asterisk</div>
						<div class="tile-body"><i class="fa fa-phone"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							движок телефонии
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getGoip4Info" data-update-interval="600000">
						<div class="tile-heading">SIM-шлюз GoIP4</div>
						<div class="tile-body"><i class="fa fa-phone"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							SIM шлюз, все Y - хорошо
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getElasticInfo">
						<div class="tile-heading">Elastic</div>
						<div class="tile-body"><i class="fa fa-search"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							результатов по запросу тарелка
						</div>
					</div>
				</div>				

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getLDAPInfo">
						<div class="tile-heading">Сервер LDAP</div>
						<div class="tile-body"><i class="fa fa-user"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							проверяем авторизацию
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/get1СInfo">
						<div class="tile-heading">Сервер 1C</div>
						<div class="tile-body"><i class="fa fa-calculator"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							проверяем ping1CToUpdateProducts
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getReacherInfo">
						<div class="tile-heading">Reacher</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							движок валидации емейлов
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/panel/getMailWizzInfo">
						<div class="tile-heading">MailWizz EMA</div>
						<div class="tile-body"><i class="fa fa-calculator"></i>
							<span class="tile-result small"></span>
						</div>
						<div class="tile-footer">
							почтовые рассылки
						</div>
					</div>
				</div>


				<h2>Очереди</h2>	

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countRainforestQueue">
						<div class="tile-heading">Rainforest API</div>
						<div class="tile-body"><i class="fa fa-amazon"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							товаров на обработку в очереди
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countSmsQueue">
						<div class="tile-heading">Очередь SMS</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							сколько смсок в очереди
						</div>
					</div>
				</div>	

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countMailQueue">
						<div class="tile-heading">Очередь Почты</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							сколько емейлов в очереди
						</div>
					</div>
				</div>

				<?php if ($this->config->get('config_country_id') == 176) { ?>
					<div class="tile-block">
						<div class="tile info-loader unknown" data-path="common/queues/countYandexStockQueue">
							<div class="tile-heading">YAM Наличие</div>
							<div class="tile-body"><i class="fa fa-yahoo"></i>
								<span class="tile-result"></span>
							</div>
							<div class="tile-footer">
								товаров на обновление наличия
							</div>
						</div>
					</div>				

					<div class="tile-block">
						<div class="tile info-loader unknown" data-path="common/queues/countYandexOrderQueue">
							<div class="tile-heading">YAM Заказы</div>
							<div class="tile-body"><i class="fa fa-yahoo"></i>
								<span class="tile-result"></span>
							</div>
							<div class="tile-footer">
								заказов на обновление статуса
							</div>
						</div>
					</div>
				<?php } ?>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countOrderTo1CQueue">
						<div class="tile-heading">1C Заказы</div>
						<div class="tile-body"><i class="fa fa-bank"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							отправка заказов в 1С
						</div>
					</div>
				</div>		

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countProductTo1CQueue">
						<div class="tile-heading">1C Товары</div>
						<div class="tile-body"><i class="fa fa-bank"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							обновление цен
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countRewardQueue">
						<div class="tile-heading">Бонусы</div>
						<div class="tile-body"><i class="fa fa-euro"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							необработанные записи начисления
						</div>
					</div>
				</div>

				<div class="tile-block">
					<div class="tile info-loader unknown" data-path="common/queues/countMailWizzQueue">
						<div class="tile-heading">MailWizz</div>
						<div class="tile-body"><i class="fa fa-envelope"></i>
							<span class="tile-result"></span>
						</div>
						<div class="tile-footer">
							обновления списков подписок
						</div>
					</div>
				</div>
				

		</div>
	</div>

	<script>
		function updateStats(elem, uri){

			$.ajax({
					url: uri,
					type: 'GET',
					async: true,
					dataType: 'json',
					beforeSend: function(){
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass('unknown');
						$(elem).children('.tile-body').children('.tile-result').html('<i class="fa fa-spinner fa-spin">');
					},		
					success: function(json){					
						$(elem).children('.tile-body').children('.tile-result').html(json.body);
						if (json.footer){
							$(elem).children('.tile-footer').html(json.footer);
						}
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass(json.class);
					},
					error: function(error){
						console.log(error);
						$(elem).removeClass('unknown').removeClass('good').removeClass('warn').removeClass('bad').addClass('bad');
					}
				});

		}

		function initUpdateStats(){
			$('div.info-loader').each(async function(index, elem){
				let uri = 'index.php?route='+ $(elem).attr('data-path') +'&nolog=1&token=<?php echo $token; ?>';

				if ($(elem).attr('data-x')){
					uri += ('&x=' + $(elem).attr('data-x'));
				}

				if ($(elem).attr('data-defer')){
					setTimeout(function(){ updateStats(elem, uri); } , $(elem).attr('data-defer')); 
					console.log($(elem).attr('data-path') + ' setTimeout ' +  $(elem).attr('data-defer'));
				} else {

					if ($(elem).attr('data-update-interval')){
						setInterval(function(){ updateStats(elem, uri); } , $(elem).attr('data-update-interval')); 
						updateStats(elem, uri);
						console.log($(elem).attr('data-path') + ' setInterval ' +  $(elem).attr('data-update-interval'));
					} else {
						setInterval(function(){ updateStats(elem, uri); } , 10000); 
						updateStats(elem, uri);
					}
				}

			});
		}

		$(document).ready(function() {			  
			initUpdateStats();
		});
	</script>

</div>
<?php echo $footer; ?>