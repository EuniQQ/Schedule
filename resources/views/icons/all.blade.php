<!-- menu icon -->
<button class="btn menuIcon" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBackdrop" 
aria-controls="offcanvasWithBackdrop">
    <i class="fa-solid fa-bars fa-lg" style="color: #616161;"></i>
</button>
<!-- setting icon -->
<button class="btn settingIcon">
    <i class="fa-solid fa-gear fa-lg " style="color: #616161;"></i>
</button>
<!-- search input -->
<input type="search" name="keyword" id="calYear" placeholder="keyword">
<!-- select year -->
<select class="m-1">
    <option value="{{$year}}">{{$year}}</option>
</select>
<!-- select month -->
<select class="m-1 ">
    <option value="01">1</option>
    <option value="02">2</option>
    <option value="03">3</option>
    <option value="04">4</option>
    <option value="05">5</option>
    <option value="06">6</option>
    <option value="07">7</option>
    <option value="08">8</option>
    <option value="09">9</option>
    <option value="10">10</option>
    <option value="11">11</option>
    <option value="12">12</option>
</select>
<!-- icon search -->
<button class="btn" type="button">
    <i class="fa-solid fa-magnifying-glass" style="color: #636363;"></i>
</button>