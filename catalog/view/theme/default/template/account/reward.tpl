<?php echo $header; ?><?php echo $column_right; ?>
<style type="text/css">
	table{
		width: 100%;
	}
	.bonus_wrap .head{
		display: flex;
		align-items: center;
		flex-direction: column;
	}
	.bonus_wrap .head .text{
		font-size: 17px;
		font-weight: 500;
		display: block;
		color: #333;
	}
	.bonus_wrap .head .value{
		font-size: 28px;
		color: #51a881;
		font-weight: 600;
		margin-bottom: 10px;
	}

	.bonus_wrap .content{
		display: flex;
		align-items: center;
		justify-content: space-evenly;
		max-width: 60%;
		margin: auto;
		margin-top: 25px;
		margin-bottom: 35px;
	}
	.bonus_wrap .content > div{
		display: flex;
		flex-direction: column;
		width: 50%;
		text-align: center;
	}
	.bonus_wrap .content .left .value{
		color: #51a881;
	}
	.bonus_wrap .content .text{
		font-size: 14px;
		color: #333;
	}
	.bonus_wrap .content .value{
		font-weight: 600;
		font-size: 22px;
		margin-bottom: 5px;
	}
	.bonus_wrap .content .right .value{
		color: #ccc;
	}



	.bonus_head{
		display: flex;
		flex-direction: column;
		justify-content: center;
		align-items: center;
		padding: 35px 0;
		background: -webkit-linear-gradient(90deg,#7ab04c,#51a881);
		background: -moz-linear-gradient(90deg,#7ab04c,#51a881);
		background: linear-gradient(90deg,#7ab04c,#51a881);
		position: relative;
		border-radius: 5px;
		margin-bottom: 40px;
	}
	.bonus_head .title{
		font-size: 33px;
		color: #fff;
		font-weight: 600;
		display: block;
		margin-bottom: 20px;
	}
	.bonus_head .description{
		font-size: 18px;
		color: #fff;
		font-weight: 500;	
	}	
	.bonus_head a{
		font-size: 18px;
		color: #fff;
		font-weight: 500;	
		text-decoration: underline;
		margin-top: 10px;
		display: block;
	}
	.bonus_head a:hover{
		text-decoration: none;
	}
	.bonus_wrap {
		display: flex;
		flex-direction: row;
		justify-content: center;
		padding: 0 50px;
		margin-bottom: 30px;
		gap: 50px;
	}
	.bonus_wrap .bonus_item{
		display: flex;
		flex-direction: column;
	}
	.bonus_wrap .bonus_item svg{
		display: block;
		margin: auto;
		margin-bottom: 15px;
	}
	.bonus_wrap .bonus_item .value{
		font-weight: 600;
		font-size: 22px;
		margin-bottom: 5px;
	}
	.bonus_wrap .bonus_item .text{
		font-size: 14px;
		color: #333;
	}
	.bonus_wrap .bonus_item.total_active .value{
		color: #51a881;
	}
	
	.bonus_wrap .bonus_item.total_queue .value{
		color: #ccc;
	}
	@media screen and (max-width: 1475px){
		.list-transactions tbody tr td,
		.list-transactions thead tr td {
		    font-size: 16px;
		    padding: 6px 13px;
		}
		.list-transactions tbody tr td{
			font-size: 15px;
		}
	}
	@media screen and (max-width: 560px){
		.bonus_wrap .content{
			width: 100%;
			max-width: 100%;
			margin-top: 15px;
			margin-bottom: 15px;
			align-items: start;
		}
		.bonus_wrap .content .value {
		    font-size: 17px;
		}
		.bonus_wrap .content .text {
		    font-size: 13px;
		    color: #333;
		    margin-bottom: 0;
		    line-height: 16px;
		}



		.list-transactions tbody tr td {
		    font-size: 13px;
		    padding: 3px 12px;
		    line-height: 16px;
		}
		.bonus_head{
			padding: 35px 0;
			margin-bottom: 20px;
		}
		.bonus_head .title{
			font-size: 22px;
		}
		.bonus_head .description{
			font-size: 14px;
			text-align: center;	
		}	
		.bonus_head a{
			font-size: 14px;
		}
		.bonus_wrap {
			flex-direction: column;
			padding: 0;
			margin-bottom: 30px;
			gap: 10px;
		}
		.bonus_wrap .bonus_item{
			display: flex;
			flex-direction: column;
			margin-bottom: 20px;
			text-align: center;
		}
		.bonus_wrap .bonus_item svg{
			display: block;
			margin: auto;
			margin-bottom: 15px;
		}
		.bonus_wrap .bonus_item .value{
			font-size: 18px;
		}
		.bonus_wrap .bonus_item .text{
			font-size: 13px;
		}












	}
</style>
<div id="content" class="account_wrap">

	
	<style>
		.list-transactions .text-danger, .list-transactions .text-success{font-size:100%}
		small {font-size:80%;}
	</style>
	
	    <?php echo $content_top; ?>
    <?php include($this->checkTemplate(dirname(FILE),'/../structured/breadcrumbs.tpl'); ?>
    <div class="wrap two_column">
        <div class="side_bar">
            <?php echo $column_left; ?>
        </div>
        <div class="account_content">
        	<div class="bonus_head">
        		
				<p class="title">Kitchen-Profi Club</p>
				<p class="description">Накапливайте бонусы и оплачивайте ими свои покупки</p>
				<a href="/faq/cashback">Подробнее</a>
        	</div>
			<div class="bonus_wrap">


				<div class="bonus_item total_active">
					<?php if ($total_active) { ?>
						<svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
							 width="100px" height="100px" viewBox="0 0 400 400" style="enable-background:new 0 0 400 400;" xml:space="preserve">
							<style type="text/css">
								.st01{fill:none;}
								.st11{fill:#57AC79;}
								.st21{fill:#FBC04F;}
							</style>
							<rect class="st01" width="400" height="400"/>
							<path class="st11" d="M321,137.6H8.2c-4.5,0-8.2-3.7-8.2-8.2V70.7c0-4.5,3.7-8.2,8.2-8.2H321c4.5,0,8.2,3.7,8.2,8.2v58.6
							C329.2,133.9,325.5,137.6,321,137.6z M16.5,121.1h296.2V78.9H16.5V121.1z"/>
							<path class="st11" d="M221.2,314.7H22.6c-4.5,0-8.2-3.7-8.2-8.2V128.8c0-4.5,3.7-8.2,8.2-8.2h283.9c4.5,0,8.2,3.7,8.2,8.2v70.6
							c0,4.5-3.7,8.2-8.2,8.2s-8.2-3.7-8.2-8.2V137H30.9v161.2h190.3c4.5,0,8.2,3.7,8.2,8.2S225.7,314.7,221.2,314.7z"/>
							<path class="st21" d="M191.6,314.7h-54V120.6h54V314.7z M154.1,298.2h21.1V137h-21.1V298.2z"/>
							<path class="st21" d="M204.3,137.6h-79.5V62.5h79.5V137.6z M141.3,121.1h46.5V78.9h-46.5V121.1z"/>
							<path class="st21" d="M185.8,78.8h-42.3V38.3h42.3V78.8z M159.9,62.4h9.4v-7.6h-9.4V62.4z"/>
							<path class="st21" d="M138.5,78.9H69.2V50.5c0-10.5,5.8-20.2,15.1-25.1c9.3-5,20.5-4.4,29.3,1.4l33.9,22.6h0
							c6,4,8.6,11.2,6.5,18.1C151.9,74.3,145.7,78.9,138.5,78.9z M85.7,62.5h51.7l-33-22C100.6,38,96,37.7,92,39.9
							c-4,2.1-6.4,6.1-6.4,10.6V62.5z"/>
							<path class="st21" d="M260,78.9h-69.3c-7.2,0-13.4-4.6-15.4-11.5c-2.1-6.8,0.5-14.1,6.5-18.1l33.9-22.6c8.8-5.8,20-6.4,29.3-1.4
							c9.3,5,15.1,14.6,15.1,25.1V78.9z M191.8,62.5l51.7,0v-12c0-4.5-2.4-8.5-6.4-10.6c-4-2.1-8.6-1.9-12.4,0.6L191.8,62.5z"/>
							<path class="st21" d="M306.5,378c-51.5,0-93.5-41.9-93.5-93.5c0-51.5,41.9-93.5,93.5-93.5S400,233,400,284.6
							C400,336.1,358.1,378,306.5,378z M306.5,207.6c-42.5,0-77,34.5-77,77s34.5,77,77,77s77-34.5,77-77S349,207.6,306.5,207.6z"/>
							<path class="st11" d="M306.5,341.2c-2.5,0-4.9-1.1-6.4-3.1l-38.8-48.4c-2.8-3.6-2.3-8.7,1.3-11.6c3.5-2.8,8.7-2.3,11.6,1.3l31,38.7
							l67.2-125.8c2.1-4,7.1-5.5,11.1-3.4c4,2.1,5.5,7.1,3.4,11.1l-73.1,136.8c-1.3,2.4-3.7,4.1-6.5,4.3
							C307,341.2,306.8,341.2,306.5,341.2z"/>
						</svg>

						<span class="value"><?php echo $total_active; ?></span>				
						<span class="text"><?php echo $text_active; ?></span>			
					<?php } ?>
				</div>

				<div class="bonus_item total_queue">
					<?php if ($total_queue) { ?>
						<svg id="Слой_12" data-name="Слой 1" xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 400 400">
								<defs>
									<style>.cls-13{fill:none;}.cls-23{fill:#57ac79;}.cls-33{fill:#fbc04f;}</style></defs><rect class="cls-13" width="400" height="400"/><path class="cls-23" d="M200.12,400A190.64,190.64,0,0,1,179.68,19.82a8.55,8.55,0,1,1,1.82,17A173.08,173.08,0,0,0,26.6,209.37c0,95.68,77.85,173.52,173.52,173.52A171.67,171.67,0,0,0,308.32,345,8.56,8.56,0,0,1,319,358.41,188.62,188.62,0,0,1,200.12,400Z" transform="translate(0 0)"/><polygon class="cls-23" points="148.24 59.34 175.55 28.71 144.9 1.42 169.49 0 200.12 27.29 172.84 57.92 148.24 59.34"/><path class="cls-33" d="M332.79,342.63a8.55,8.55,0,0,1-6.23-14.41,177.3,177.3,0,0,0,11.52-13.58A8.55,8.55,0,1,1,351.67,325,193,193,0,0,1,339,339.94,8.51,8.51,0,0,1,332.79,342.63ZM359,307a8.43,8.43,0,0,1-4.18-1.1,8.55,8.55,0,0,1-3.27-11.65A173.88,173.88,0,0,0,362,272.13a8.55,8.55,0,1,1,15.95,6.19,190,190,0,0,1-11.46,24.28A8.55,8.55,0,0,1,359,307Zm18.52-47.69a8.59,8.59,0,0,1-8.34-10.5A171.92,171.92,0,0,0,373,224.65a8.56,8.56,0,0,1,17,1.48,190.64,190.64,0,0,1-4.21,26.52A8.56,8.56,0,0,1,377.49,259.28Zm4.45-51a8.56,8.56,0,0,1-8.53-8.11,175.89,175.89,0,0,0-3-24.26,8.56,8.56,0,0,1,16.8-3.27,192.89,192.89,0,0,1,3.27,26.64,8.56,8.56,0,0,1-8.1,9Zm-9.79-50.2a8.57,8.57,0,0,1-8.09-5.75,172,172,0,0,0-9.63-22.44,8.56,8.56,0,0,1,15.21-7.84,190.3,190.3,0,0,1,10.59,24.66A8.56,8.56,0,0,1,375,157.62,8.39,8.39,0,0,1,372.15,158.1Zm-23.44-45.45a8.55,8.55,0,0,1-7-3.6,175.26,175.26,0,0,0-15.5-18.89A8.56,8.56,0,1,1,338.65,78.4a194.31,194.31,0,0,1,17,20.74,8.56,8.56,0,0,1-7,13.51ZM313.54,75.48a8.54,8.54,0,0,1-5.32-1.86,173.77,173.77,0,0,0-20.14-13.86A8.56,8.56,0,0,1,296.77,45a191.51,191.51,0,0,1,22.11,15.23,8.55,8.55,0,0,1-5.34,15.24Zm-44.08-26a8.54,8.54,0,0,1-3.26-.65A171.8,171.8,0,0,0,243,41.18a8.55,8.55,0,0,1,4.22-16.58,189,189,0,0,1,25.5,8.45,8.56,8.56,0,0,1-3.26,16.47Z" transform="translate(0 0)"/><path class="cls-23" d="M305.38,121.44H105.85a8.56,8.56,0,0,1,0-17.11H305.38a8.56,8.56,0,1,1,0,17.11Z" transform="translate(0 0)"/><path class="cls-23" d="M305.38,322.25H105.85a8.56,8.56,0,0,1,0-17.11H305.38a8.56,8.56,0,1,1,0,17.11Z" transform="translate(0 0)"/><path class="cls-23" d="M281.6,322.25H128.93a8.55,8.55,0,0,1-7.55-12.57l43.74-82.29A42.23,42.23,0,0,1,177.62,213a42.15,42.15,0,0,1-11.79-13.84L122.08,116.9a8.55,8.55,0,0,1,7.55-12.57H282.3a8.55,8.55,0,0,1,7.56,12.57l-43.75,82.29a42.12,42.12,0,0,1-12.5,14.36,42.18,42.18,0,0,1,11.8,13.84l43.75,82.29a8.56,8.56,0,0,1-7.56,12.57ZM143.17,305.14H267.36L230.3,235.42a25.11,25.11,0,0,0-10.69-10.56,12.78,12.78,0,0,1,.21-22.88A25.49,25.49,0,0,0,231,191.16l37.06-69.72H143.87l37.07,69.72a25.08,25.08,0,0,0,10.68,10.56,12.77,12.77,0,0,1-.2,22.88,25.56,25.56,0,0,0-11.19,10.82Z" transform="translate(0 0)"/><path class="cls-33" d="M205.27,196.11A13.14,13.14,0,0,1,194,189.9l-29.69-47.68a9.56,9.56,0,0,1,8.14-14.6l64.38-.19a9.69,9.69,0,0,1,8.39,4.84,9.58,9.58,0,0,1-.09,9.6l-28.45,47.8a13.14,13.14,0,0,1-11.27,6.44ZM186,144.69l19.19,30.82,18.4-30.93Z" transform="translate(0 0)"/><circle class="cls-33" cx="202.49" cy="235.05" r="7.71"/><circle class="cls-33" cx="220.6" cy="250.46" r="7.71"/><circle class="cls-33" cx="204.67" cy="267.24" r="7.71"/>

						</svg>
						<span class="value"><?php echo $total_queue; ?></span>
						<span class="text"><?php echo $text_inactive; ?></span>									
					<?php } ?>
				</div>

				<div class="bonus_item total">
					<?php if ($total) { ?>

						<svg version="1.1" id="Слой_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
								 width="100px" height="100px" viewBox="0 0 400 400" style="enable-background:new 0 0 400 400;" xml:space="preserve">
							<style type="text/css">
								.st02{fill:none;}
								.st12{fill:#57AC79;}
								.st22{fill:#FBC04F;}
							</style>
							<rect class="st02" width="400" height="400"/>
							<path class="st12" d="M221.3,349.4H28.7c-14.9,0-27.1-12.1-27.1-27.1c0-14.9,12.1-27.1,27.1-27.1h21.6c4.6,0,8.4,3.8,8.4,8.4
							c0,4.6-3.8,8.4-8.4,8.4H28.7c-5.7,0-10.3,4.6-10.3,10.3c0,5.7,4.6,10.3,10.3,10.3h192.5c4.6,0,8.4,3.8,8.4,8.4
							C229.6,345.6,225.9,349.4,221.3,349.4z"/>
							<path class="st12" d="M185,97.5c-1.2,0-2.5-0.3-3.7-0.9c-4.1-2.1-5.8-7.1-3.8-11.2l17.2-34.8c0.1-0.1,0.1-0.2,0.2-0.3
							c0.5-0.9,1.2-2.6,1-4.7c-0.3-2.8-2.2-5.1-3.8-5.8c0,0,0,0-0.1,0c-0.2,0-0.7,0.1-1.1,0.1c-2.1,0.3-5.4,0.8-10.4,0
							c-3.7-0.6-8.8-1.4-13.6-5.2c-3.2-2.5-4.4-4.8-5.4-6.9c-0.7-1.3-1.4-2.8-3.3-5.3c-2-2.8-3.3-4.4-4.8-5.1c-0.5-0.2-1.1-0.4-1.7-0.4
							c-0.9-0.1-1.8-0.1-2.7-0.1c-1.8,0.1-3.3,0.5-3.9,0.7c-0.9,0.3-2,1.8-4.3,4.9c-1.8,2.5-2.6,4-3.3,5.3c-1,2.1-2.2,4.4-5.4,6.9
							c-4.8,3.8-9.9,4.6-13.6,5.2c-5,0.8-8.3,0.4-10.4,0c-0.4-0.1-0.9-0.1-1.1-0.1c0,0,0,0-0.1,0c-1.7,0.7-3.4,3.3-3.8,5.8
							c-0.2,1.9,0.5,3.6,1.2,5.1c5.2,11.1,11.2,23,17.1,34.6c2.1,4.1,0.5,9.2-3.6,11.3c-4.1,2.1-9.2,0.5-11.3-3.6
							c-6-11.7-12-23.9-17.4-35.1c-1-2.1-3.6-7.5-2.7-14.4c0.9-7.2,5.7-15.9,14.3-19.3c4.2-1.7,7.4-1.2,9.7-0.9c1.6,0.2,2.8,0.4,5.3,0
							c2.9-0.5,4.6-0.8,6-1.8c0.2-0.1,0.2-0.2,0.2-0.2c0.1-0.1,0.3-0.6,0.5-1.1c0.8-1.6,2-4,4.7-7.7c3.2-4.4,6.3-8.6,12.1-10.8
							c2.4-0.9,5.6-1.5,8.6-1.7c1.7-0.1,3.5-0.1,5.2,0.1c2.4,0.2,4.7,0.8,6.9,1.8c5.8,2.5,8.9,6.8,11.6,10.6c2.6,3.6,3.9,6,4.7,7.7
							c0.2,0.4,0.5,0.9,0.6,1.1c0,0,0.1,0.1,0.2,0.2c1.3,1.1,3.1,1.4,6,1.8c2.5,0.4,3.7,0.2,5.3,0c2.3-0.3,5.5-0.8,9.7,0.9
							c7.5,2.9,13.2,10.7,14.3,19.3c0.6,5-0.4,10.2-2.8,14.7l-17.2,34.7C191.1,95.8,188.1,97.5,185,97.5z"/>
							<path class="st12" d="M26.3,285.8C26.3,285.8,26.3,285.8,26.3,285.8c-4.7,0-8.4-3.8-8.4-8.5l0.1-8c0-5.1,0.1-9,0.1-9.8
							c0.6-32.1,21.2-70.8,25.3-78.3c5.4-9.8,14.4-26.1,31.6-43c10-9.8,21.2-18.4,33.4-25.5c4-2.3,9.1-1,11.5,3c2.3,4,1,9.1-3,11.5
							c-11,6.4-21.1,14.1-30.1,23c-15.5,15.2-23.4,29.7-28.7,39.2c-2.3,4.2-22.7,41.8-23.2,70.5c0,0.8,0,4.6-0.1,9.6l-0.1,8
							C34.6,282.1,30.9,285.8,26.3,285.8z"/>
							<path class="st12" d="M25.9,312.5c-0.2,0-0.5,0-0.7,0c-4.6-0.4-8-4.4-7.7-9c0.1-1,0.2-6.3,0.3-26.1c0-4.6,3.8-8.3,8.4-8.3
							c0,0,0.1,0,0.1,0c4.6,0,8.3,3.8,8.3,8.5c-0.2,23.2-0.3,26.2-0.4,27.3C33.9,309.2,30.3,312.5,25.9,312.5z"/>
							<path class="st12" d="M268.4,232.4c-3.5,0-6.7-2.2-7.9-5.7c-6-17.6-13.9-32.5-16.2-36.6c-5.3-9.5-13.2-24-28.7-39.2
							c-9-8.9-19.1-16.6-30.1-23c-4-2.3-5.3-7.5-3-11.5c2.3-4,7.5-5.3,11.5-3c12.2,7.1,23.4,15.7,33.4,25.5
							c17.2,16.9,26.2,33.3,31.6,43c2.5,4.4,11,20.4,17.4,39.3c1.5,4.4-0.8,9.1-5.2,10.6C270.3,232.3,269.3,232.4,268.4,232.4z"/>
							<path class="st22" d="M190.7,128.4h-80.3c-10.2,0-18.6-8.3-18.6-18.6V99.3c0-10.2,8.3-18.6,18.6-18.6h80.3
							c10.2,0,18.6,8.3,18.6,18.6v10.5C209.3,120.1,201,128.4,190.7,128.4z M110.4,97.5c-1,0-1.8,0.8-1.8,1.8v10.5c0,1,0.8,1.8,1.8,1.8
							h80.3c1,0,1.8-0.8,1.8-1.8V99.3c0-1-0.8-1.8-1.8-1.8H110.4z"/>
							<path class="st22" d="M217.9,231.2c-0.7,0-1.4,0-2.1-0.1c-4.6-0.6-8.8-3.4-11.5-7.5c-2.9-4.4-3.8-9.9-2.4-15.1c1-4,1.6-8,1.8-12
							c0.2-4.1-0.3-15.2-7.5-31.3c-6.5-14.4-16.1-27.5-28.6-39l11.3-12.3c14.1,13,25.1,27.9,32.5,44.4c4.3,9.6,6.7,17.9,7.8,24.6
							c-0.6-4.3-1.4-9-2.8-14.1c-2.2-8-6.2-22.9-18.7-37c-2.4-2.7-4.9-5.2-7.6-7.5l10.9-12.8c3.3,2.8,6.4,5.9,9.3,9.1
							c15.1,17,20,35,22.4,43.7c3.2,11.6,4.6,24.1,4.2,35.2c-0.3,6.2-1,12.5-2.3,18.6l-0.1,0.7l-0.2,0.6
							C231.4,226.6,224.8,231.2,217.9,231.2z M219.7,205.1c-0.4,2.6-0.9,5.1-1.6,7.6l0,0.1c-0.2,0.6-0.1,1,0.1,1.3
							c0.1-0.1,0.1-0.1,0.2-0.2C218.9,211,219.4,208,219.7,205.1z"/>
							<path class="st22" d="M180.5,219c-2.4,0-4.7-0.5-6.9-1.5c-7.2-3.4-10.9-11.8-8.8-19.9l0.2-0.7c2.2-6,3.8-12.3,4.7-18.7
							c0.7-5.1,2.1-14.5,0-26.1c-1.7-9.7-5.4-18.9-10.9-27.4l14.1-9.1c6.8,10.4,11.3,21.7,13.4,33.7c2.5,14.2,0.8,25.8,0,31.4
							c-1,7.1-2.8,14.2-5.2,21l0.2-0.4c1.9-4.3,3.5-8.8,4.6-13.3c3.4-13.5,3.3-27.7-0.2-42l16.3-4c4.2,17.1,4.3,34,0.2,50.2
							c-1.4,5.4-3.2,10.7-5.5,15.8c-1.8,4.5-5.3,8.1-9.8,9.9C184.8,218.5,182.6,219,180.5,219z"/>
							<path class="st22" d="M303.2,400c-52.5,0-95.1-42.7-95.1-95.1s42.7-95.1,95.1-95.1s95.1,42.7,95.1,95.1S355.7,400,303.2,400z
							M303.2,226.5c-43.2,0-78.4,35.2-78.4,78.4s35.2,78.4,78.4,78.4s78.4-35.2,78.4-78.4S346.4,226.5,303.2,226.5z"/>
							<path class="st12" d="M354.9,313.2h-98.6c-4.6,0-8.4-3.8-8.4-8.4c0-4.6,3.8-8.4,8.4-8.4h98.6c4.6,0,8.4,3.8,8.4,8.4
							C363.3,309.5,359.5,313.2,354.9,313.2z"/>
							<path class="st12" d="M305.6,362.6c-4.6,0-8.4-3.8-8.4-8.4v-98.7c0-4.6,3.8-8.4,8.4-8.4s8.4,3.8,8.4,8.4v98.7
										C313.9,358.8,310.2,362.6,305.6,362.6z"/>	
						</svg>

						<span class="value"><?php echo $total; ?></span>
						<span class="text"><?php echo $text_all; ?></span>						
					<?php } ?>
				</div>
			</div>
			
			
			
			
			
			
			
			<?php if ($queues) { ?>
				<div class="table-adaptive">
					<table class="list list-transactions">
						<thead>
							<tr>
								<td class="left"><?php echo $column_date_added; ?></td>
								<td class="left"><?php echo $column_order_id; ?></td>
								<td class="left"><?php echo $column_description; ?></td>
								<td class="right"><?php echo $column_date_activate_points; ?></td>
								<td class="right"><?php echo $column_date_activate; ?></td>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($queues as $queue) { ?>
								<tr>
									<td class="left">
										<small><?php echo $queue['date_added']; ?></small>
									</td>
									
									<td class="left">
										<?php if ($queue['order_id']) { ?>
											<a href="<?php echo $queue['order_href']; ?>"><?php echo $queue['order_id']; ?></a>
										<?php } ?>
									</td>
									
									<td class="left">
										<?php if ($queue['description']) { ?>
											<small><?php echo $queue['description']; ?></small>
										<?php } ?>
									</td>
									
									<td class="left" style="white-space:nowrap;">
										<small><span class="text-success"><?php echo $queue['points']; ?></span></small>
									</td>
									
									<td class="left">
										<?php if ($queue['date_activate']) { ?>
											<small><span class="text-success"><?php echo $queue['date_activate']; ?></span></small>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>	
			<?php } ?>
			
			<div class="table-adaptive">
				<table class="list list-transactions">
					<thead>
						<tr>
							<td class="left"><?php echo $column_date_added; ?></td>
							<td class="left"><?php echo $column_order_id; ?></td>
							<td class="left"><?php echo $column_description; ?></td>
							<td class="right"><?php echo $column_points; ?></td>
							<td class="right"><?php echo $column_points_paid; ?></td>
							<td class="right"><?php echo $column_date_paid; ?></td>
							<td class="right"><?php echo $column_active_points; ?></td>
							<td class="right"><?php echo $column_date_inactivate; ?></td>
						</tr>
					</thead>
					<tbody>
						<?php if ($rewards) { ?>
							<?php foreach ($rewards  as $reward) { ?>
								<tr>
									<td class="left">
										<small><?php echo $reward['date_added']; ?></small>
									</td>
									
									<td class="left">
										<?php if ($reward['order_id']) { ?>
											<a href="<?php echo $reward['order_href']; ?>"><?php echo $reward['order_id']; ?></a>
										<?php } ?>
									</td>
									
									<td class="left">
										<?php if ($reward['description']) { ?>
											<small><?php echo $reward['description']; ?></small>
										<?php } ?>
									</td>
									
									<td class="left" style="white-space:nowrap;">
										<?php if ($reward['points']) { ?>
											<small><span class="<?php echo $reward['class']; ?>"><?php echo $reward['points']; ?></span></small>
										<?php } ?>
									</td>
									
									<td class="left" style="white-space:nowrap;">
										<?php if ($reward['points_paid']) { ?>
											<small><span class="text-danger"><?php echo $reward['points_paid']; ?></span></small>
										<?php } ?>
									</td>
									
									<td class="left">
										<?php if ($reward['date_paid']) { ?>
											<small><?php echo $reward['date_paid']; ?></small>
										<?php } ?>
									</td>
									
									<td class="left" style="white-space:nowrap;">
										<?php if ($reward['points_active']) { ?>
											<small><span class="text-success"><b><?php echo $reward['points_active']; ?></b></span></small>
										<?php } ?>
									</td>
									
									<td class="left">
										<?php if ($reward['date_inactivate']) { ?>
											<small><span class="text-danger"><?php echo $reward['date_inactivate']; ?></span></small>
										<?php } ?>
									</td>
								</tr>
							<?php } ?>
							<?php } else { ?>
							<tr>
								<td class="center" colspan="8"><?php echo $text_empty; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<div class="pagination"><?php echo $pagination; ?></div>
			<div class="buttons">
				<div class="right"><a href="<?php echo $continue; ?>" class="btn btn-acaunt"><?php echo $button_continue; ?></a></div>
			</div>
			<?php echo $content_bottom; ?></div>
	</div>
</div>
<?php echo $footer; ?>