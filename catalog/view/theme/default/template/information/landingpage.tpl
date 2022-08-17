<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>

<style type="text/css">
	#content{
		padding-top: 25px;
	}
	#content a{
		/*border-bottom: 1px dashed #51a881;*/
	    color: #51a881;		
	    font-size: 18px;
		font-weight: 500;
		font-family: 'Montserrat',sans-serif;
		margin: 0 0 10px 0;
		display: inline-block;
	}
	#content .title_content_bd{
		font-size: 24px;
		font-weight: 500;
		font-family: 'Montserrat',sans-serif;
	}
	#content .txt_bd{
		font-size: 18px;
		font-weight: 500;
		font-family: 'Montserrat',sans-serif;
		margin: 0 0 10px 0;
	}
	#content .img_BD{
		display: flex;
		flex-direction: column;
		align-items: center;
		padding: 25px 0;
	}
	#content .img_BD img{

	}
	#content .logo{

	}
	#content em span{
		color: #8c8c8c;
	}
	@media screen and (max-width: 1050px){
		#content{
			padding-left: 25px;
			padding-right: 25px;
		}
		#content .img_BD img{
			width: 100% !important;
			height: auto !important;
		}
		
	}
	@media screen and (max-width: 780px){
		#content a,
		#content .txt_bd{
			font-size: 16px;
		}
		#content .title_content_bd{
			font-size: 20px;
		}
	}
</style>

<div id="content"><?php echo $content_top; ?>

<?php echo $description; ?>


  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>
