<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<img src="{{"data:image/jpeg;base64,".$pix->encodedImage}}" alt="">
<br>
<center>


<input type="text" id="p2" value="{{$pix->payload}}">

</center>
<center>
    <button onclick="copyToClipboard('#p1')" class="btn">

        Copiar Link
    </button>
</center>
<br><br>


<p style="opacity: 0;margin-bottom: -20px" id="p1">{{$pix->payload}}</p>
<script src="{{ asset('admin/bower_components/jquery/dist/jquery.min.js') }}"></script>
<script>

    function copyToClipboard(element) {
        console.log('teste');
        var $temp = $("<input>");
        $("body").append($temp);
        $temp.val($(element).text()).select();
        document.execCommand("copy");
        $temp.remove();
    }
</script>
</body>



</html>
