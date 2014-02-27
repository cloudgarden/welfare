
<style>
body {margin:0px; padding:0px; font-family:dotum;}
#zipbox {color:#555555}
#zipbox .title {border-top:2px solid #5c8dc9;  border-bottom:#dfdfdf solid 1px; font-size:14px; font-weight:bold; padding:20px 10px 20px 28px;}
#zipbox .ment {text-:center;line-height:150%;padding:12px 0 12px 0;color:#808080; font-size:11px; letter-spacing:-1px;}
#zipbox .searchform {margin:0 20px 0 20px;padding:15px;border-top:#e5e5e5 solid 1px; border-bottom:#e5e5e5 solid 1px;text-:center;background:#fdfdfd;}
#zipbox .searchform span {position:relative;top:2px;}
#zipbox .searchform .input {width:200px;}
#zipbox .searchform .btn {position:relative;top:1px;}
#zipbox .searchform input[type="image"] {position:relative; top:5px;}
#zipbox .resultbox {margin:0 20px 0 20px;height:220px;overflow:auto; border-top:2px solid #5c8dc9;  border-bottom:#dfdfdf solid 1px; padding-top:5px; #555555;}
#zipbox .resultbox table {width:90%;}
#zipbox .resultbox td {padding:3px 0 3px 0;letter-spacing:-1px;}
#zipbox .resultbox .td1 {width:60px;text-:center;color:#555;}
#zipbox .resultbox .td2 a {color:#555;}
#zipbox .resultbox .td2 a:hover {text-decoration:none; color:#999;}
#zipbox .resultbox .none {text-:center;padding:100px 0 0 50px;}
#zipbox .bottom {margin:20px 20px 0 20px;height:60px;text-:center;}
#zipbox label ,#zipbox  .method{font-size:11px; letter-spacing:-1px;}

</style>


<div id="zipbox">
	<div class="title">
	주소검색
	</div>
	<div class="ment">
		ㆍ찾고자하는 주소의 동(읍/면/리)을 입력하세요. (서초동, 오창읍, 비암리)
	</div>
	<div class="searchform">

		<div class="control">
			<input type="radio" name="method" value="0" id="old" checked="checked" /><label for="old">지번 주소 검색</label>
			<input type="radio" name="method" value="1" id="new" /><label for="new">도로명 주소 검색</label>
		</div>
		<div class="clear" style="height:7px;"></div>

		<span class="method hide">지역명 </span><span class="method hide">도로명 </span>
		<input type="text" value="" name="zipkey" class="input" />
		<a href="#" class="btn btn-small btn-inverse">검색</a>
	</div>
	<div class="ment">
		검색 결과를 클릭하시면 자동으로 주소가 입력됩니다.
	</div>
	<div class="resultbox">
	
	</div>

	<div class="bottom">
		<a href="#" class="btn btn-small btn-inverse">창닫기</a>
	</div>

</div>
