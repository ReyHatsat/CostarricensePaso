
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12 text-center pt-5 mt-5">
            <h1 class="text-dark">Contact us Anytime</h1>
            <h4 class="text-secondary">For any inquiries, dont hessitate to contact us!</h4>
            <hr>
        </div>
        <div class="col-md-6 container my-5 rounded p-0"
            style="background:url('<?=PATH_ASSETS?>img/CF021490 byn.jpg'); background-size:cover;">

            <div style="background:rgba(0,0,0, 0.4); width:100%; height:100%;" class="px-5 py-5">
                <h3 class="text-light font-weight-bold"> Contact Information: </h3>
                <h5 class="text-light font-weight-bold">
                    <i class="fa fa-envelope"></i> -
                    Email:
                    <a href="mailto:info@costarricense-paso.com" class="text-light"> info@costarricense-paso.com </a>
                </h5>
                <hr style="width:50%;">
                <h5 class="text-light font-weight-bold">
                    <i class="fa fa-phone"></i> -
                    Phone: +1 (888) 888-88-88
                </h5>
                <hr style="width:50%;">
                <h5 class="text-light font-weight-bold">
                    <i class="fa fa-info-circle"></i>
                    - Costarricense de Paso USA is based at the Lazy Double D Ranch in Blieblerville Texas, USA
                </h5>
            </div>

        </div>
        <div class="col-md-6 container mt-4 py-5">
            <div class="justify-content-center text-center">
                <div class="" id="register_form" style="background-color: #ffffff">
                    <div class="user">
                        <div class="form-group">
                            <label class="col-form-label-sm">Your name</label>
                            <input autocomplete="on" class="form-control" type="text" id="c_name">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label-sm">Your email</label>
                            <input autocomplete="on" class="form-control" type="text" id="c_email">
                        </div>
                        <div class="form-group">
                            <label class="col-form-label-sm">Your message:</label>
                            <textarea autocomplete="on" class="form-control bg-light" id="c_msg"></textarea>
                        </div>

                        <button class="btn-lg btn-block btn btn-dark " id="send_contact">
                            <i class="fa fa-send"></i>
                            Send
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script>

    let contact_can_send = true;

    // Widnow loaded validation.
    window.onload = function() {

        trigger('#send_contact', 'click', function(){
            let validate = ( contact_can_send ) ? validateContact() : false;
            if(validate){
                contact_can_send = false;
                setTimeout(() => { contact_can_send = true }, 60000 * 5 );
                Fnon.Hint.Success('Message Sent!', {position:'center-center'})
                return;
            }

            if(!contact_can_send){
                Fnon.Hint.Warning('Your message was already sent!', {position:'center-center'})
            }

        })

        function validateContact(){

            let error = [];
            let data = {
                name:findone('#c_name'),
                email:findone('#c_email'),
                msg:findone('#c_msg')
            }

            //remove errors
            findall('.form-control').forEach( el => {
                el.classList.remove('border')
                el.classList.remove('border-danger')
            })


            if(data.name.value == ''){ error.push(data.name) }

            if(data.email.value == '' || !validateEmail(data.email.value)){
                error.push(data.email)
            }

            if(data.msg.value == ''){ error.push(data.msg) }


            if(error.length > 0){
                error.forEach( err => {
                    err.classList.add('border')
                    err.classList.add('border-danger')
                })
                Fnon.Hint.Danger('Validate your inputs', {position:'center-center'})
                return false;
            }


            data = {
                name:data.name.value,
                email:data.email.value,
                msg:data.msg.value,
            }

            return data
        }

    };

</script>
