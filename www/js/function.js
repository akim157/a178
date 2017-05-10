function sizePic(){
	avatar = document.getElementById('avatar').value;
	img=document.getElementById('pic');
	img.width=60+20*avatar;
}
function agreeForm(f){
	if(f.reg.checked) f.submit.disabled=0;
	else f.submit.disabled=1;
}
function click_font_feedback() {
	if (document.getElementById('nav_').style.display=='none'){
		document.getElementById('nav_').style.display='inherit';
		document.getElementById('icon_f').ClassName='A2';
	} else 
	{
		document.getElementById('nav_').style.display='none';
	}
} 
function click_font_feedback_2() {
	if (document.getElementById('nav_2').style.display=='none'){
		document.getElementById('nav_2').style.display='inherit';		
	} else 
	{
		document.getElementById('nav_2').style.display='none';
	}
} 
