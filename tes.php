<html>
<head>
<link href="assests/css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
<script src="assests/js/lib/jquery/jquery-1.11.2.min.js"></script>
<script src="assests/js/lib/bootstrap/bootstrap.js"></script>
</head>
<body>
<script>


garages = [
    {
        name: '',
        country: '',
        cars:[
            {
                model: 'BMW',
                year: ''
            }
        ],
        hook: function(){}
    },
    {
        name: '',
        country: '',
        cars:[
            {
                model: 'Honda',
                year: ''
            }
        ],
        hook: function(){}
    }

];
$.each(garages, function(i,v){
    $.each(v.cars, function(i2, v2){
	    alert(JSON.stringify(v2));
        alert(v2.model);
    })
});
</script>
</body>
</html>