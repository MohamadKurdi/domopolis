<?php if (!$this->customer->isLogged() && $this->config->get('social_auth_google_app_id') && $this->config->get('social_auth_google_enable_sso_widget')) { ?>
    <script>
        var google_auth_script = document.createElement('script');
        google_auth_script.onload = function () {
            var handleCredentialResponse = function(CredentialResponse){
             $.ajax({
                url: "<?php echo $this->url->link('api/google/login'); ?>",
                method: "POST",
                dataType: "json",
                data: {
                    credential: CredentialResponse.credential
                },
                success: function(json) {
                    console.log("[GAUTH]: Success got response, parsing");
                    if (json.status == true){
                        console.log("[GAUTH] " + json.message);
                        window.location.reload();
                    } else {
                        console.log("[GAUTH] Error, status: " + json.status);
                    }
                },
                error: function(xhr, status, error) {
                    console.log("[GAUTH] Error, possibly 401");
                }
            });
         }

           google.accounts.id.initialize({
            client_id:      '<?php echo $this->config->get('social_auth_google_app_id'); ?>',
            context:        "signin",
            auto_select:    "true",
            itp_support:    "true",
            nonce:          "<?php echo $google_auth_nonce; ?>",
            callback:       handleCredentialResponse
        });
           google.accounts.id.prompt();
       };
       google_auth_script.src = 'https://accounts.google.com/gsi/client';

       document.head.appendChild(google_auth_script);
   </script>
<?php } ?>
