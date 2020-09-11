
    var error = new Howl({
        src: ["/assets/audios/smb_mariodie.wav","/assets/audios/smb_mariodie.ogg","/assets/audios/smb_mariodie.mp3"],
    });
    var success = new Howl({
        src: ["/assets/audios/smb_coin.wav","/assets/audios/smb_coin.ogg","/assets/audios/smb_coin.mp3"],
    });

    $(function() {
        $('#barcode').focus();
        $('html').click(function(){
            $('#barcode').focus();
        });
    });

    function changeBackground(color){
        $(".panel").css("background-color", color);
        setTimeout(function(){$(".panel").css("background-color", "white");},1230);
    }

    $(function () {
        $('#barcodeform').submit(function( e ) {
            e.preventDefault();
            var barcode=$('#barcode').val();

            axios.get("/orders/barcode/read"+"?barcode="+barcode).then(function (response) {
                console.log(response.data);
                    if (response.data.return == 'True') {
                        success.play();
                        $('#mario').css('visibility','visible');$('#not_ok').css('visibility','hidden');
                        toastr.success(response.data.msg);
                    } else {
                        changeBackground('red'); error.play();$('#mario').css('visibility','hidden');$('#not_ok').css('visibility','visible');
                        toastr.error(response.data.msg);
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });
            $('#barcode').val('');
        });
    });