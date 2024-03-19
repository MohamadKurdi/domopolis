<?php echo $header; ?>
<?php include($this->checkTemplate(dirname(__FILE__),'/../structured/breadcrumbs.tpl')); ?>
<?php echo $content_top; ?>

<style type="text/css">
    .wrap.info_description{
        max-width: 1090px;
        background: #FFFFFF;
        padding: 30px 25px;
        border: 1px solid #DDE1E4;
        box-shadow: 0px 4px 82px rgba(150, 159, 168, 0.17);
        border-radius: 12px;
    }
    .wrap.info_description h1 span,
    .wrap.info_description h2,
    .wrap.info_description h3,
    .wrap.info_description h1{
        font-family: 'Unbounded', sans-serif;
        margin-top: 0 !important;
        font-weight: 500;
        font-size: 22px !important;
        line-height: 27px !important;
        color: #121415;
        margin-bottom: 16px !important;
    }
    .wrap.info_description p{
        font-weight: 400;
        font-size: 16px;
        line-height: 24px;
        color: #696F74;
    }
    .information-4 .wrap.info_description,
    .information-33 .wrap.info_description,
    .information-30 .wrap.info_description,
    .information-31 .wrap.info_description,
    .information-29 .wrap.info_description{
        max-width: 1300px;
        background: transparent;
        padding: 0 40px;
        border: 0;
        border-radius: 0;
        box-shadow: none;
    }
    @media screen and (max-width: 560px) {
        .information-4 .wrap.info_description,
        .information-33 .wrap.info_description,
        .information-30 .wrap.info_description,
        .information-31 .wrap.info_description,
        .information-29 .wrap.info_description{
            padding: 0 10px;
        }
        .wrap.info_description h1 span, .wrap.info_description h2, .wrap.info_description h3, .wrap.info_description h1{
            font-size: 20px !important;
            line-height: 25px !important;
            margin-bottom: 16px !important;
        }
        .wrap.info_description{
            padding: 20px;
            width: calc(100% - 10px);
            margin: auto;
        }
    }
    /*How to order*/
    .information-4 .wrap.info_description p,
    .information-33 .wrap.info_description p,
    .information-33 .wrap.info_description li,
    .information-31 .wrap.info_description p,
    .information-31 .wrap.info_description ul li,
    .information-29 .wrap.info_description ul li,
    .information-29 .wrap.info_description p{
        font-weight: 400;
        font-size: 16px;
        line-height: 160.52%;
        color: #121415;
        margin-bottom: 16px;
    }
    .information-29 .wrap.info_description ul li{
        display: flex;
        gap: 16px;
    }
    .information-29 .wrap.info_description ul li::before{
        content: '';
        width: 40px;
        min-width: 40px;
        height: 40px;
        background-color: rgba(235, 50, 116, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-position: center;
        background-repeat: no-repeat;
    }
    .information-29 .wrap.info_description ul li:nth-of-type(1)::before{
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjAiIHZpZXdCb3g9IjAgMCAyMiAyMCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTQuOTk4NiA2QzE0Ljk5ODYgNy4wNjA4NyAxNC41NzcyIDguMDc4MjggMTMuODI3IDguODI4NDNDMTMuMDc2OSA5LjU3ODU3IDEyLjA1OTUgMTAgMTAuOTk4NiAxMEM5LjkzNzc1IDEwIDguOTIwMzMgOS41Nzg1NyA4LjE3MDE4IDguODI4NDNDNy40MjAwNCA4LjA3ODI4IDYuOTk4NjEgNy4wNjA4NyA2Ljk5ODYxIDZNMi42MzE4MyA1LjQwMTM4TDEuOTMxODMgMTMuODAxNEMxLjc4MTQ1IDE1LjYwNTkgMS43MDYyNiAxNi41MDgyIDIuMDExMyAxNy4yMDQyQzIuMjc5MyAxNy44MTU3IDIuNzQzNjQgMTguMzIwNCAzLjMzMDggMTguNjM4MkMzLjk5OTA4IDE5IDQuOTA0NDcgMTkgNi43MTUyNSAxOUgxNS4yODJDMTcuMDkyOCAxOSAxNy45OTgxIDE5IDE4LjY2NjQgMTguNjM4MkMxOS4yNTM2IDE4LjMyMDQgMTkuNzE3OSAxNy44MTU3IDE5Ljk4NTkgMTcuMjA0MkMyMC4yOTEgMTYuNTA4MiAyMC4yMTU4IDE1LjYwNTkgMjAuMDY1NCAxMy44MDE0TDE5LjM2NTQgNS40MDEzOEMxOS4yMzYgMy44NDg3NSAxOS4xNzEzIDMuMDcyNDMgMTguODI3NSAyLjQ4NDg2QzE4LjUyNDcgMS45Njc0NCAxOC4wNzM5IDEuNTUyNiAxNy41MzMxIDEuMjkzODVDMTYuOTE5IDEgMTYuMTQgMSAxNC41ODIgMUw3LjQxNTI1IDFDNS44NTcyNCAxIDUuMDc4MjMgMSA0LjQ2NDEzIDEuMjkzODRDMy45MjMzNiAxLjU1MjYgMy40NzI1MSAxLjk2NzQ0IDMuMTY5NzQgMi40ODQ4NkMyLjgyNTkxIDMuMDcyNDMgMi43NjEyMiAzLjg0ODc1IDIuNjMxODMgNS40MDEzOFoiIHN0cm9rZT0iI0VCMzI3NCIgc3Ryb2tlLXdpZHRoPSIyIiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz48L3N2Zz4=);
    }
    .information-29 .wrap.info_description ul li:nth-of-type(2)::before{
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTgiIGhlaWdodD0iMTgiIHZpZXdCb3g9IjAgMCAxOCAxOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNOS40MzkwNSAxMS4zNTYzQzEwLjAxOSAxMS43NjQ5IDEwLjYyNzEgMTIuMTA4NCAxMS4yNTM5IDEyLjM2ODRDMTEuNjQzMSAxMi41MDk2IDEyLjI1MzggMTIuMTA5OSAxMi43MDI4IDExLjgxNkMxMi44MTUgMTEuNzQyNiAxMi45MTcyIDExLjY3NTcgMTMuMDAzMiAxMS42MjU2TDEzLjAzMTUgMTEuNjA5OEMxMy40NTQ1IDExLjM3MjIgMTMuOTI0MSAxMS4xMDg1IDE0LjUyODEgMTEuMjM1NkMxNS4wNzA3IDExLjM0NzEgMTYuOTMyMyAxMi44MTQxIDE3LjQ0NjggMTMuMzM0MUMxNy43ODM2IDEzLjY2ODQgMTcuOTcwNyAxNC4wMjEyIDE3Ljk5ODcgMTQuMzgzM0MxOC4wNTQ4IDE1LjcyOTcgMTYuMjQ5NCAxNy4yNDMyIDE1LjgxOSAxNy41MDMyQzE0LjkwMjMgMTguMTcxOCAxMy42NzY4IDE4LjE2MjUgMTIuMTk4NyAxNy40OTM5QzEwLjYxNzggMTYuODUzMiA4LjcyODA4IDE1LjUwNjkgNi45MDM4OSAxMy44NzI3QzYuMjUwODkgMTMuMjg3NyA0Ljk5Njg3IDEyLjA1NiA0LjY0IDExLjY0NDZDMi43OTcxIDkuNjQ4MjggMS4yNDQxOSA3LjUwMzM3IDAuNTE0NTE3IDUuNzY3MDJDMC4xNjgzODggNS4wMzM0OCAwIDQuMzU1NjYgMCAzLjc1MjExQzAgMy4xNTc4NSAwLjE2ODM4NyAyLjYyODU5IDAuNDk1ODA3IDIuMTczNjFDMC42OTIyNTggMS44MzAwNSAyLjI3MzIyIC0wLjA0NTU3OTkgMy42NjcwOSAwLjAwMDg0NzI3QzQuMDEzMjIgMC4wMzc5ODc2IDQuMzY4NzEgMC4yMTQ0MDkgNC43MTQ4NCAwLjU0ODY4QzUuMjM4NzEgMS4wNTkzNyA2Ljc0NDgzIDIuOTA3MTUgNi44NTcwOSAzLjQ1NDk4QzYuOTg1MTcgNC4wNDUyMSA2LjcxOTYgNC41MTExMiA2LjQ4MDM0IDQuOTMwODdMNi40NjQxOSA0Ljk1OTJDNi40MTAyNyA1LjA1MjM3IDYuMzM3MTYgNS4xNjI4OSA2LjI1NzIzIDUuMjgzNzJDNS45NjI3OCA1LjcyODg0IDUuNTc1ODEgNi4zMTM4MiA1LjcxNDg3IDYuNjg2MjdDNi4wNjE5MyA3LjU0MDUyIDYuNTU3NzQgOC4zNzYxOSA3LjE1NTUxIDkuMTQ2ODdDNy44MTg5MyA5Ljk0MDA4IDguODU5MDUgMTAuOTQ3OCA5LjQzOTA1IDExLjM1NjNaIiBmaWxsPSIjRUIzMjc0Ii8+PC9zdmc+);
    }
    .information-29 .wrap.info_description ul li:nth-of-type(3)::before{
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBkPSJNMTIgMjFDMTYuOTcwNiAyMSAyMSAxNi45NzA2IDIxIDEyQzIxIDcuMDI5NDQgMTYuOTcwNiAzIDEyIDNDNy4wMjk0NCAzIDMgNy4wMjk0NCAzIDEyQzMgMTMuNDg3NiAzLjM2MDkzIDE0Ljg5MSA0IDE2LjEyNzJMMyAyMUw3Ljg3MjggMjBDOS4xMDkwNCAyMC42MzkxIDEwLjUxMjQgMjEgMTIgMjFaIiBzdHJva2U9IiNFQjMyNzQiIHN0cm9rZS13aWR0aD0iMiIgc3Ryb2tlLWxpbmVjYXA9InJvdW5kIiBzdHJva2UtbGluZWpvaW49InJvdW5kIi8+PHBhdGggZD0iTTEzLjUgMTJDMTMuNSAxMi44Mjg0IDEyLjgyODQgMTMuNSAxMiAxMy41QzExLjE3MTYgMTMuNSAxMC41IDEyLjgyODQgMTAuNSAxMkMxMC41IDExLjE3MTYgMTEuMTcxNiAxMC41IDEyIDEwLjVDMTIuODI4NCAxMC41IDEzLjUgMTEuMTcxNiAxMy41IDEyWiIgZmlsbD0iI0VCMzI3NCIvPjxwYXRoIGQ9Ik0xOCAxMkMxOCAxMi44Mjg0IDE3LjMyODQgMTMuNSAxNi41IDEzLjVDMTUuNjcxNiAxMy41IDE1IDEyLjgyODQgMTUgMTJDMTUgMTEuMTcxNiAxNS42NzE2IDEwLjUgMTYuNSAxMC41QzE3LjMyODQgMTAuNSAxOCAxMS4xNzE2IDE4IDEyWiIgZmlsbD0iI0VCMzI3NCIvPjxwYXRoIGQ9Ik05IDEyQzkgMTIuODI4NCA4LjMyODQzIDEzLjUgNy41IDEzLjVDNi42NzE1NyAxMy41IDYgMTIuODI4NCA2IDEyQzYgMTEuMTcxNiA2LjY3MTU3IDEwLjUgNy41IDEwLjVDOC4zMjg0MyAxMC41IDkgMTEuMTcxNiA5IDEyWiIgZmlsbD0iI0VCMzI3NCIvPjwvc3ZnPg==);
    }
    /*How to order end*/

    /*delivery*/
    .information-31 .wrap.info_description ul{
        list-style-type: disc;
        padding-left: 20px;
        margin-bottom: 15px;
    }
    .information-33 .wrap.info_description li,
    .information-31 .wrap.info_description ul li{
        margin-bottom: 5px;
    }
    /*delivery end*/


    /*payament*/
    .information-30 #payment_block{
        background: #FFFFFF;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 32px;
    }
    .information-30 #payment_block tr td{
        border: 1px solid rgba(204, 212, 220, 0.7);
        border-radius: 11px;
        text-align: center;
        font-weight: 400;
        font-size: 16px;
        line-height: 160.52%;
        color: #000000;
        padding: 20px;
    }
    .information-30 #payment_block tr td p{
        font-weight: 400;
        font-size: 16px;
        line-height: 160.52%;
        color: #000000;
    }
    .information-30 #payment_block table{
        border-spacing: 16px;
        border-collapse: initial;   
        background: transparent;
        margin-bottom: 0;
    }
    .information-30 #payment_block_pay_page{
        margin-bottom: 32px;
    }
    .information-30 #payment_block_pay_page ul{
        gap: 15px;
    }
    .information-30 #payment_block_pay_page ul li{
        display: flex;
        background: #FFFFFF;
        border-radius: 4px;
        width: 124px;
        height: 80px;
        align-items: center;
        justify-content: center;
        margin: 0;
    }
    .information-30 #payment_block_pay_page ul li svg{
        max-width: 65px;
        height: auto;
    }
    .information-30 .main-wrap{
        display: none !important;
    }
    @media screen and (max-width: 560px){
        .information-30 .wrap.info_description .info_list{
            display: none;
        }
        .information-30 #payment_block_pay_page ul li{
            width: 100%;
            margin: 0;
        }
        .information-30 .main-wrap{
            background: #FFFFFF;
            border-radius: 12px;
            padding: 10px;
            margin-bottom: 32px;
            display: flex !important;
            flex-direction: column;
            gap: 10px;
        }
        .information-30 .main-wrap .payment{
            padding: 0;
            border: none;
            margin-bottom: 0;
            flex-direction: column;
            display: flex;
            gap: 8px;
        }
        .information-30 .main-wrap .payment > div:not(.head) > div,
        .information-30 .main-wrap .payment .head{
            border: 1px solid rgba(204, 212, 220, 0.7);
            border-radius: 11px;
            padding: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            font-weight: 400;
            font-size: 16px;
        }
        .information-30 .main-wrap .payment:nth-of-type(2) > div:not(.head){
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .information-30 .main-wrap .payment ul{
            display: flex;
            flex-direction: column;
            list-style: disc;
            padding-left: 20px;
            width: 100%;
        }
        .information-30 .main-wrap .payment ul li{
            text-align: left;
            margin-bottom: 0;
            font-weight: 400;
            font-size: 16px;
        }
        .information-30 .main-wrap .payment ul li::after,
        .information-30 .main-wrap .payment ul li::before{
            display: none;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head){
            display: grid;
            grid-template-columns: 1fr 1fr;
            grid-template-rows: 1fr 1fr 1fr;
            gap: 8px;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head) > div:nth-of-type(1){
            grid-column-start: 1;
            grid-column-end: 1;
            grid-row-start: 1;
            grid-row-end: 1;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head) > div:nth-of-type(2){
            grid-column-start: 2;
            grid-column-end: 2;
            grid-row-start: 1;
            grid-row-end: 1;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head) > div:nth-of-type(3){
            grid-column-start: 1;
            grid-column-end: 1;
            grid-row-start: 2;
            grid-row-end: 2;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head) > div:nth-of-type(4){
            grid-column-start: 1;
            grid-column-end: 1;
            grid-row-start: 3;
            grid-row-end: 3;
        }
        .information-30 .main-wrap .payment:nth-of-type(1) > div:not(.head) > div:nth-of-type(5){
            grid-column-start: 2;
            grid-column-end: 2;
            grid-row-start: 2;
            grid-row-end: 4;
        }
    }
    /*payament end*/

    /*return*/
    .information-33 ol{
        list-style: decimal;
        padding-left: 20px;
        margin-bottom: 15px;
    }
    /*return end*/

    /* about page */
    .information-4 .about_company .top_content{
        background-color: #BFEA43;
        border-radius: 20px;
        padding: 51px 65px 40px;
        background-image: url(/catalog/view/theme/dp/img/about_top.png);
        background-repeat: no-repeat;
        background-position: bottom right;
        background-size: 33%;
        margin-bottom: 50px;
    }
    .information-4 .about_company .top_content p{
        max-width: 670px;
    }
    .information-4 .about_company .content .image_block{
        background-image: url(/catalog/view/theme/dp/img/about.jpg);
        width: 759px;
        height: 426px;
        background-position: center;
        background-repeat: no-repeat;
        border-radius: 16px;
        overflow: hidden;
        margin: 30px 0;
    }
    .information-4 .about_company .content ul li{
        display: flex;
        gap: 16px;
        margin-bottom: 16px;
    }
    .information-4 .about_company .content ul li::before{
        content: '';
        width: 40px;
        min-width: 40px;
        height: 40px;
        background-color: rgba(235, 50, 116, 0.1);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background-position: center;
        background-repeat: no-repeat;
        background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj48cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTguMDIwNzggMEM3LjY4Njk0IDAgNy40Mzc3NCAwLjE4MTg4NyA3LjI5NjMzIDAuMzEwMDZDNy4xNDA3OSAwLjQ1MTA1MiA3LjAwMzc3IDAuNjI5NDg4IDYuODg1NDYgMC44MDc4NzJDNi42NDY1OCAxLjE2ODAzIDYuNDEyMjcgMS42Mzg1NiA2LjE5OTkyIDIuMTE3NjJDNS43NzE5OSAzLjA4MzA0IDUuMzg1NTkgNC4xOTMwOSA1LjE4MzY0IDQuNzk5MDdDNS4xODA2OCA0LjgwNzk2IDUuMTcxOTUgNC44MTQ5NSA1LjE2MTM5IDQuODE1MzFDNC41Mjg2NSA0LjgzNjcgMy4zNjkwNCA0Ljg5MzQ1IDIuMzU4MzMgNS4wNDE3QzEuODU5MDEgNS4xMTQ5NCAxLjM1NjE0IDUuMjE1OTIgMC45NjM1MTQgNS4zNjIxNEMwLjc3MDE4OCA1LjQzNDE0IDAuNTU5MzU2IDUuNTMyOSAwLjM4NTQ1NCA1LjY3NjE4QzAuMjA5NDQ5IDUuODIxMTkgMCA2LjA3NTMzIDAgNi40NDE4MkMwIDYuNjk1MjkgMC4wOTU4ODU2IDYuOTE5NjcgMC4xODI2ODEgNy4wNzk3OUMwLjI3NTcxMyA3LjI1MTQyIDAuMzk4NjY0IDcuNDIxNTEgMC41MzAxNzcgNy41ODIxMkMwLjc5Mzc3NCA3LjkwNDA0IDEuMTQyMzIgOC4yNDg2NiAxLjUwMjY4IDguNTc2NDFDMi4yMjY4NCA5LjIzNTA1IDMuMDY1NDYgOS44ODU0MyAzLjU0MjU5IDEwLjI0NUMzLjU1MDEyIDEwLjI1MDcgMy41NTM2MiAxMC4yNjAxIDMuNTUwNTEgMTAuMjcwNEMzLjM2NzI3IDEwLjg3MiAzLjA0OTA0IDExLjk3MjkgMi44NDY1OSAxMi45OTM5QzIuNzQ2MDQgMTMuNTAxMSAyLjY2ODM5IDE0LjAxNTkgMi42NTY3MyAxNC40NTA3QzIuNjUwOTMgMTQuNjY3IDIuNjYwNDUgMTQuODkwOSAyLjcwMzcgMTUuMDk2MkMyLjc0Mzk3IDE1LjI4NzQgMi44MzI0NyAxNS41NTU4IDMuMDU4OTYgMTUuNzU2QzMuMzEzMTYgMTUuOTgwNyAzLjYxOTExIDE2LjAxMDUgMy44MzA5NSAxNS45OTc0QzQuMDQ3NTcgMTUuOTg0IDQuMjY0NTIgMTUuOTIxMyA0LjQ1Nzg2IDE1Ljg0ODNDNC44NDgzIDE1LjcwMDkgNS4yODkwMiAxNS40NTQzIDUuNzExNjEgMTUuMTg4QzYuNTY1MzEgMTQuNjUwMSA3LjQ2OTI4IDEzLjk1MiA3Ljk4MzM2IDEzLjU0MTVDNy45OTI2NiAxMy41MzQgOC4wMDYzMyAxMy41MzM4IDguMDE2MDkgMTMuNTQxNkM4LjUzMDA2IDEzLjk1MjUgOS40MzQ3NyAxNC42NTExIDEwLjI5NDkgMTUuMTg5NEMxMC43MjA4IDE1LjQ1NiAxMS4xNjYxIDE1LjcwMjYgMTEuNTYzMyAxNS44NDk4QzExLjc2MDUgMTUuOTIyOCAxMS45Nzk2IDE1Ljk4NDQgMTIuMTk4MiAxNS45OTc1QzEyLjQxMjggMTYuMDEwMyAxMi43MTAzIDE1Ljk4MDIgMTIuOTY0MSAxNS43NzE3QzEzLjIwMiAxNS41NzY0IDEzLjMwMDggMTUuMzA4MiAxMy4zNDY3IDE1LjEwOTJDMTMuMzk1IDE0Ljg5OTcgMTMuNDA2MiAxNC42NzIyIDEzLjQwMDggMTQuNDU0MkMxMy4zOTAxIDE0LjAxNjIgMTMuMzA4NCAxMy40OTk0IDEzLjIwMjYgMTIuOTkyNEMxMi45ODk0IDExLjk3MTIgMTIuNjUyMiAxMC44NyAxMi40NTcgMTAuMjY1OEMxMi40NTM2IDEwLjI1NTEgMTIuNDU3MiAxMC4yNDUxIDEyLjQ2NSAxMC4yMzkzQzEyLjk0NDYgOS44Nzc2NiAxMy43ODE0IDkuMjI4MTggMTQuNTAzMSA4LjU3MTE3QzE0Ljg2MjIgOC4yNDQyMiAxNS4yMDk0IDcuOTAwNTggMTUuNDcxOSA3LjU3OTUyQzE1LjYwMjkgNy40MTkzNCAxNS43MjU0IDcuMjQ5NjYgMTUuODE4MSA3LjA3ODM5QzE1LjkwNDYgNi45MTg1NSAxNiA2LjY5NDcgMTYgNi40NDE4MkMxNiA2LjA3NTcyIDE1Ljc5MSA1LjgyMTcgMTUuNjE1MSA1LjY3NjY1QzE1LjQ0MTQgNS41MzMzNiAxNS4yMzA4IDUuNDM0NiAxNS4wMzc3IDUuMzYyNjFDMTQuNjQ1NyA1LjIxNjQxIDE0LjE0MzUgNS4xMTU0MiAxMy42NDQ4IDUuMDQyMTZDMTIuNjM1NCA0Ljg5Mzg3IDExLjQ3NjcgNC44MzY5NiAxMC44NDI2IDQuODE1NDRDMTAuODMxOSA0LjgxNTA4IDEwLjgyMzMgNC44MDgxMiAxMC44MjA0IDQuNzk5MTVDMTAuNjIzIDQuMTkxMzYgMTAuMjQ2MyAzLjA4MjI3IDkuODI3MDIgMi4xMTgxOUM5LjYxODk5IDEuNjM5ODQgOS4zODg4MiAxLjE2OTc2IDkuMTUyOTUgMC44MDk3NTlDOS4wMzYxMyAwLjYzMTQ2NiA4LjkwMDMzIDAuNDUyNjc5IDguNzQ1MzMgMC4zMTEyMjlDOC42MDQwNCAwLjE4MjI3OSA4LjM1NTEyIDAgOC4wMjA3OCAwWiIgZmlsbD0iI0VCMzI3NCIvPjwvc3ZnPg==);
    }
    @media screen and (max-width: 560px) {
        .information-4 .about_company .top_content{
            padding: 20px 20px 320px;
            background-size: 534px;
            background-position: center bottom;
        }
        .information-4 .about_company .content .image_block{
            width: 100%;
            height: 206px;
            background-size: cover;
        }
        .information-30 #payment_block tr td {
            font-size: 14px;
            line-height: 17px;
            padding: 10px;
        }
        .information-30 #payment_block tr td p {
            font-size: 14px;
            line-height: 17px;
        }
    }
    /* about page end*/
    
</style>

<div class="wrap info_description">
    <?php echo $content_left; ?>
    <?php echo $description; ?>
    <?php echo $content_right; ?>
</div>




<?php echo $content_bottom; ?>

<?php echo $footer; ?>
