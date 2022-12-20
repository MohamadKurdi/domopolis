<?php echo $header; ?>
<style type="text/css">
	#content-error404{
		background: -webkit-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));
		background: -moz-linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));
		background: linear-gradient(90deg, rgb(122, 176, 76), rgb(81, 168, 129));	    
		margin-bottom: 20px;	    
	}
	.error_content{
		display: flex;
		justify-content: space-between;
		align-items: center;
		padding: 50px 0;
		position: relative;
	}	
	.error_content .left-block {
		margin-right: 30px;
		width: 40%;
		text-align: center;
	}
	.error_content .left-block svg{
		width: 300px;
		height: 300px;
		fill: #fff;
	}
	.error_content .right-block{
		width: 60%;
		color: #fff;
		position: relative;
	}
	.error_content .right-block h3{
		font-size: 30px;
		margin-bottom: 25px;
	}
	.error_content .right-block p {
		font-size: 20px !important;
		line-height: 34px;
	}
	#content-error404 .error_content p .promo-error {
		background: #ffffff;
		color: #2e5844;
		font-weight: 500;
		padding: 3px 15px;
	}
	.right-block .btn-group .btn{
		background: #ffc34f;
		font-size: 18px;
	}
	.right-block .btn-group .btn:hover{
		background: #e16a5d;
	}
	.right-block .select-category{
		position: relative;
		display: inline-block;
	}
	.right-block .select-category b{
		color: #fff !important;
	}
	#content-error404 .right-block .select-category:after{
		content: '';
		background-image: url(data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4bWxuczpzdmdqcz0iaHR0cDovL3N2Z2pzLmNvbS9zdmdqcyIgdmVyc2lvbj0iMS4xIiB3aWR0aD0iNTEyIiBoZWlnaHQ9IjUxMiIgeD0iMCIgeT0iMCIgdmlld0JveD0iMCAwIDUxMi4wMDggNTEyLjAwOCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgNTEyIDUxMiIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSIgY2xhc3M9IiI+PGcgdHJhbnNmb3JtPSJtYXRyaXgoLTEuODM2OTcwMTk4NzIxMDI5N2UtMTYsLTEsMSwtMS44MzY5NzAxOTg3MjEwMjk3ZS0xNiwwLjAwMTAwMDQwNDM1NzkxMDE1NjIsNTEyLjAwNzk3MzY3MDk1OTYpIj48cGF0aCB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHN0eWxlPSIiIGQ9Ik01MDEuMzQyLDUzLjI1NmMtNS44OTEsMC0xMC42NjcsNC43NzYtMTAuNjY3LDEwLjY2N2MtMC4xNTMsMTM1LjQzLTEwOS45MDMsMjQ1LjE4LTI0NS4zMzMsMjQ1LjMzMyAgSDM2LjQyNGwxMDkuODAzLTEwOS43ODFjNC4wOTItNC4yMzcsMy45NzUtMTAuOTktMC4yNjItMTUuMDgzYy00LjEzNC0zLjk5Mi0xMC42ODctMy45OTItMTQuODIsMGwtMTI4LDEyOCAgYy00LjE3MSw0LjE2LTQuMTc5LDEwLjkxNC0wLjAxOSwxNS4wODVjMC4wMDYsMC4wMDYsMC4wMTMsMC4wMTMsMC4wMTksMC4wMTlsMTI4LDEyOGM0LjA5Myw0LjIzNywxMC44NDUsNC4zNTQsMTUuMDgzLDAuMjYyICBjNC4yMzctNC4wOTMsNC4zNTQtMTAuODQ1LDAuMjYyLTE1LjA4M2MtMC4wODYtMC4wODktMC4xNzMtMC4xNzYtMC4yNjItMC4yNjJMMzYuNDI0LDMzMC41ODloMjA4LjkxNyAgYzE0Ny4yMDgtMC4xNjUsMjY2LjUwMi0xMTkuNDU5LDI2Ni42NjctMjY2LjY2N0M1MTIuMDA4LDU4LjAzMiw1MDcuMjMzLDUzLjI1Niw1MDEuMzQyLDUzLjI1NnoiIGZpbGw9IiNmZmZmZmYiIGRhdGEtb3JpZ2luYWw9IiMyMTk2ZjMiIGNsYXNzPSIiLz48cGF0aCB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIGQ9Ik0xMzguNjc1LDQ1OC41ODljLTIuODMxLDAuMDA1LTUuNTQ4LTEuMTE1LTcuNTUyLTMuMTE1bC0xMjgtMTI4Yy00LjE2NC00LjE2NS00LjE2NC0xMC45MTcsMC0xNS4wODNsMTI4LTEyOCAgYzQuMjM3LTQuMDkyLDEwLjk5LTMuOTc1LDE1LjA4MywwLjI2MmMzLjk5Miw0LjEzNCwzLjk5MiwxMC42ODcsMCwxNC44MkwyNS43NTgsMzE5LjkyM2wxMjAuNDQ4LDEyMC40NDggIGM0LjE3MSw0LjE2LDQuMTc5LDEwLjkxNCwwLjAxOSwxNS4wODVDMTQ0LjIyNCw0NTcuNDYxLDE0MS41MDgsNDU4LjU4OSwxMzguNjc1LDQ1OC41ODl6IiBmaWxsPSIjZmZmZmZmIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIi8+PHBhdGggeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiBkPSJNMjQ1LjM0MiwzMzAuNTg5SDEwLjY3NWMtNS44OTEsMC0xMC42NjctNC43NzYtMTAuNjY3LTEwLjY2N2MwLTUuODkxLDQuNzc2LTEwLjY2NywxMC42NjctMTAuNjY3aDIzNC42NjcgIGMxMzUuNDMxLTAuMTUzLDI0NS4xODEtMTA5LjkwMywyNDUuMzMzLTI0NS4zMzNjMC01Ljg5MSw0Ljc3Ni0xMC42NjcsMTAuNjY3LTEwLjY2N3MxMC42NjcsNC43NzYsMTAuNjY3LDEwLjY2NyAgQzUxMS44NDQsMjExLjEzLDM5Mi41NDksMzMwLjQyNSwyNDUuMzQyLDMzMC41ODl6IiBmaWxsPSIjZmZmZmZmIiBkYXRhLW9yaWdpbmFsPSIjMDAwMDAwIiBzdHlsZT0iIiBjbGFzcz0iIi8+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PGcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48L2c+PC9nPjwvc3ZnPg==);
		background-repeat: no-repeat;
		background-size: contain;
		width: 130px;
		height: 145px;
		position: absolute;
		/* right: 128px; */
		left: 100%;
		top: 17px;
	}
	@media screen and (max-width: 1600px) {
		.error_content .left-block{
			width: 30%;
		}
		
		.error_content .right-block{
			width: 65%;
		}
	}
	@media screen and (max-width: 1400px) {
		.error_content .left-block{
			width: 24%;
		}
		.error_content .left-block svg{
			width: 100%;
			height: auto;
			max-width: 400px;
		}
		.error_content .right-block{
			width: 70%;
		}
		.error_content .right-block p {
			font-size: 18px !important;
			line-height: 30px;
		}
		#content-error404 .right-block .select-category:after{
			top: 13px;
		}
	}
	@media screen and (max-width: 1200px) {
		.error_content .right-block h3{
			font-size: 28px;
		}
		#content-error404 .right-block .select-category:after{
			content: unset;
		}
	}
	@media screen and (max-width: 1000px) {
		.error_content {
			justify-content: center;
			align-items: center;
			flex-direction: column;
		}
		.error_content .left-block{
			margin-right: 0;
			margin-bottom: 20px;
			width: 100%;
		}
		.error_content .right-block{
			text-align: center;

		}
		#content-error404 .error_content hr{
			width: 75%;
			margin: auto
		}
		#content-error404 .caption-txt{
			width: 90%;
			margin: auto
		}
		.error_content .left-block svg{
			max-width: 300px;
		}
	}
	@media screen and (max-width: 576px) {
		.error_content .left-block svg{
			max-width: 200px;
		}
		.error_content .right-block{
			width: 100%;
		}
		.error_content .right-block h3 {
			font-size: 20px;
		}
		.error_content .right-block p {
			font-size: 16px !important;
			line-height: 26px;
		}
	}
</style>
<div class="page404">
	<div id="content-error404">
		<div class="wrap">

			<div class="error_content">
				<div class="left-block">		
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -34 512.00004 512">
						<path d="m256 95.421875c-23.90625 0-43.351562 19.445313-43.351562 43.351563v39.191406c0 23.902344 19.449218 43.351562 43.351562 43.351562s43.351562-19.449218 43.351562-43.351562v-39.191406c0-23.90625-19.449218-43.351563-43.351562-43.351563zm12.632812 82.542969c0 6.964844-5.667968 12.628906-12.632812 12.628906s-12.628906-5.664062-12.628906-12.628906v-39.191406c0-6.964844 5.664062-12.632813 12.628906-12.632813s12.632812 5.667969 12.632812 12.632813zm0 0"/>
						<path d="m177.152344 95.421875c-8.480469 0-15.359375 6.878906-15.359375 15.359375v31.371094h-25.261719v-31.371094c0-8.480469-6.878906-15.359375-15.359375-15.359375-8.484375 0-15.359375 6.878906-15.359375 15.359375v46.730469c0 8.484375 6.875 15.363281 15.359375 15.363281h40.621094v33.082031c0 8.480469 6.878906 15.359375 15.359375 15.359375 8.484375 0 15.359375-6.878906 15.359375-15.359375v-95.175781c0-8.484375-6.875-15.359375-15.359375-15.359375zm0 0"/>
						<path d="m390.828125 95.421875c-8.484375 0-15.359375 6.878906-15.359375 15.359375v31.371094h-25.261719v-31.371094c0-8.480469-6.878906-15.359375-15.363281-15.359375-8.480469 0-15.359375 6.878906-15.359375 15.359375v46.730469c0 8.484375 6.878906 15.363281 15.359375 15.363281h40.625v33.082031c0 8.480469 6.875 15.359375 15.359375 15.359375 8.480469 0 15.359375-6.878906 15.359375-15.359375v-95.175781c0-8.484375-6.875-15.359375-15.359375-15.359375zm0 0"/>
						<path d="m496.640625 0h-481.28125c-8.480469 0-15.359375 6.878906-15.359375 15.359375v344.96875c0 8.480469 6.878906 15.359375 15.359375 15.359375h160.769531v36.863281h-46.082031c-8.484375 0-15.359375 6.875-15.359375 15.359375s6.875 15.359375 15.359375 15.359375h251.90625c8.484375 0 15.359375-6.875 15.359375-15.359375s-6.875-15.359375-15.359375-15.359375h-46.082031v-36.863281h160.769531c8.480469 0 15.359375-6.878906 15.359375-15.359375v-344.96875c0-8.480469-6.878906-15.359375-15.359375-15.359375zm-465.921875 30.71875h450.5625v246.664062h-450.5625zm274.433594 381.832031h-98.304688v-36.863281h98.304688zm176.128906-67.582031h-450.5625v-36.863281h450.5625zm0 0"/>
					</svg>
				</div>
				<div class="right-block">
					
					<?php if ($this->config->get('config_language') <> 'uk') { ?>
						<h3>Запрашиваемая страница не существует</h3>
						<p>Воспользуйтесь промокодом  <span class="promo-error">KP-404</span> и получите скидку на первый заказ</p>
						<p class='select-category'>Перейдите на главную страницу или выберите подходящую <b>категорию</b></p>
						<div class="btn-group">
							<a class="btn btn-acaunt" href="/">На главную</a>	
						</div>			
					<?php } else { ?>
						<h3>Сторінка, яку ви запитуєте, не існує</h3>
						<p>Скористайтсь промокодом <span class="promo-error">KP-404</span> та отримайте знижку на перше замовлення</p>
						<p class='select-category'>Перейдіть на головну сторінку або виберіть відповідну <b>категорію</b></p>
						<div class="btn-group">
							<a class="btn btn-acaunt" href="/">На головну</a>
						</div>
					<?php } ?>
				</div>
			</div>
			
		</div>

		
		
	</div>
	<?php echo $content_bottom; ?>
</div>	
<?php echo $footer; ?>