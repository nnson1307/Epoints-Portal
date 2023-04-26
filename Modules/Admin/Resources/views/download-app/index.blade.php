<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">


<div class="text-center">
    <?php
        $brandCode = session()->get('brand_code');
    ?>
    <a href="{{asset("static/backend/file_app/$brandCode/app_staff.apk")}}">
        <h4>Download App Staff</h4>
    </a>
</div>

