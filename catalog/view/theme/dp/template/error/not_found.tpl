<?php echo $header; ?>
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<style type="text/css">
    .clearfix:before,
	.clearfix:after {
	    display: table;

	    content: ' ';
	}
.clearfix:after {
    clear: both;
}
.page-404 .outer {

    display: table;

    width: 100%;
    height: 100%;
    padding-top: 10%;
    padding-bottom: 10%;
    background: #f0f0f0 !important;
}
.page-404 .outer .middle {
    display: table-cell;

    vertical-align: middle;
}
.page-404 .outer .middle .inner {
    width: 300px;
    margin-right: auto;
    margin-left: auto;
}
.page-404 .outer .middle .inner .inner-circle {
    height: 300px;

    border-radius: 50%;
    background-color: #ffffff;
}
.page-404 .outer .middle .inner .inner-circle:hover i {
    color: #51A62D!important;
    background-color: #f5f5f5;
    box-shadow: 0 0 0 15px #51A62D;
}
.page-404 .outer .middle .inner .inner-circle:hover span {
    color: #51A62D;
}
.page-404 .outer .middle .inner .inner-circle i {
    font-size: 5em;
    line-height: 1em;

    float: right;

    width: 1.6em;
    height: 1.6em;
    margin-top: -.7em;
    margin-right: -.5em;
    padding: 20px;

    -webkit-transition: all .4s;
            transition: all .4s;
    text-align: center;

    color: #f5f5f5!important;
    border-radius: 50%;
    background-color: #51A62D;
    box-shadow: 0 0 0 15px #f0f0f0;
}
.page-404 .outer .middle .inner .inner-circle span {
    font-size: 11em;
    font-weight: 700;
    line-height: 1.2em;
    font-family: 'PT Sans',Helvetica,sans-serif;
    display: block;

    -webkit-transition: all .4s;
            transition: all .4s;
    text-align: center;

    color: #e0e0e0;
}
.page-404 .outer .middle .inner .inner-status {
    font-size: 20px;

    display: block;

    margin-top: 20px;
    margin-bottom: 5px;

    text-align: center;
    font-weight: 600;
    color: #51A62D;
}
.page-404 .outer .middle .inner .inner-detail {
    line-height: 1.4em;

    display: block;

    margin-bottom: 10px;

    text-align: center;

    color: #999999;
}
.page-404 .btn-info{
	color: #fff;
	font-weight: 600;
	background: #51A62D;
	transition: background .3s ease;
	-webkit-touch-callout: none;
	-webkit-user-select: none;
	-khtml-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	cursor: pointer;
	display: inline-block;
	padding: 14px 30px;
	margin: 15px 0;
	outline: none;
	border: none;
	text-transform: uppercase;
	width: 100%;
}
.page-404 .btn-info:hover{
	background: #21aeac;
}
@media screen and (max-width: 560px){
	.page-404 .outer {

	    height: 100vh;
	    padding-top: 0;
	    padding-bottom: 0;
	}
}
</style>

<div class="page-404">
    <div class="outer">
        <div class="middle">
            <div class="inner">
                <!--BEGIN CONTENT-->
                <div class="inner-circle"><i class="fa fa-home"></i><span>404</span></div>
                <span class="inner-status">Запрошуваної сторінки немає</span>
                <span class="inner-detail">
                    <a class="btn btn-info mtl" href="/">На головну</a>
                </span>
            </div>
        </div>
    </div>
    <?php echo $content_bottom; ?>
</div>

<?php echo $footer; ?>