function nayta(id, tyyli)
{
	var i=document.getElementById(id);
	if(tyyli.length==0) tyyli='block';
	if(i!=null) i.style.display=tyyli;
}
function tyyli(id, tyyli)
{
	var i=document.getElementById(id);
	if(i!=null) i.className=tyyli;
}
function piilota(id)
{
	var i=document.getElementById(id);
	if(i!=null) i.style.display='none';
}
function valitse(id)
{
	var i=document.getElementById(id);
	if(i!=null) i.focus();
}

$(window).load(function(){
	if($('#menu_fix')[0]){
	
		var menuOffset = $('#menu_fix')[0].offsetTop;

		$(document).bind('ready scroll', function() {
			var docScroll = $(document).scrollTop();

			if (docScroll >= menuOffset) {
				$('#menu_fix').addClass('fixed');
			} else {
				$('#menu_fix').removeClass('fixed');            
			}

		});
	}	
});