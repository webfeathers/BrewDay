$('.datepicker').datepicker({
	autoclose:true,
	format:'yyyy-mm-dd',
	orientation: "top auto"
})

function startBrew(brewId){
	$('#start_brew_id').val(brewId);
	$('#startBrew').submit();
}
function completeBrew(brewId){
	console.log('complete brew # ' + brewId);
	$('#complete_brew_id').val(brewId);
	$('#completeBrew').submit();
}