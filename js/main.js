function tenNumber(number) {
	if (number < 10) return "0" + number;
	else return number;
};

function formatTime(timestamp, onlyDate = false) {
	var data = new Date(timestamp * 1000);
	var string = tenNumber(data.getDate()) + ".";
		string+= tenNumber(data.getMonth() + 1) + ".";
		string+= tenNumber(data.getFullYear());

	if (!onlyDate) {
		string+= " - ";
		string+= tenNumber(data.getHours()) + ":";
		string+= tenNumber(data.getMinutes());
	}

	return string;
}

$(document).ready(function(){
	$(".formatTime").each(function(){
		var timestamp = $(this).html();
		var onlyDate = $(this).hasClass("time");
		var time = formatTime(timestamp, !onlyDate);
		$(this).html(time);
	});

	$(".reply").click(function(){
		var parent = $(this).parents(".comment").eq(0);
		var id = parent.data("id");
		var name = parent.find(".name").html();
		$("#id_parent").val(id);
		$("#user_name").val(name);

		$("html, body").animate({
			scrollTop: $(".response").offset().top
		}, "slow");

	});
});

$("#send").click(function(){
	var parent = $("#id_parent").val();
	var message = $("#msgsend").val();

	$.ajax({
		url: "api/message",
		type: "POST",
		data: {parent, message},
		success: function(res) {
			console.log(res);
			if (res.success == true) {
				document.location.reload();
			}
			else {
				alert(`Error: ${res.error}`);
			}
		},
		error: function(e) {
			console.log(e);
		}
	});

	return false;
});	