var _img_rows = 0;
var _img_row_child = 0;
$(function(){

	//$("body").append('<div id="ImgPreviewList"></div>');

	$(document).delegate("img.img-preview","click",function(){

		_img_rows++;
		var img_id = "img-preview-"+_img_rows;
		$(this).attr("id",img_id).addClass("chk-preview");
        create_model_preview(img_id,false);
		$("#ModalPreview"+img_id).css({"display":"block"});

	});


	$(document).delegate("img.img-preview-child","click",function(){

		_img_row_child++;
		var  img_id = "img-preview-child-"+_img_row_child;
       	$(this).attr("id",img_id).addClass("chk-preview");
 		create_model_preview(img_id,true);
		$("#ModalPreview"+img_id,parent.document).css({"display":"block"});

	});

	$(document).delegate(".preview-close","click",function(){
		$(".modal-preview").css({"display":"none"});
		$(".modal-preview").remove();
		//if($(".modal-preview",parent.document).size()>0) $(".modal-preview",parent.document).css({"display":"none"});
	});

});

function create_model_preview(img_id,child)
{
	var src = $("#"+img_id).attr("src").replace("thumb_list/","").replace("thumb_edit/","");
	var html = "";
	html += '<div id="ModalPreview'+img_id+'" class="modal modal-preview">';
    html += '<span class="close preview-close">&times;</span>';
    html += '<img class="modal-content" src="'+src+'">';
	html += '</div>';

	if(!child) {
		$("#ImgPreviewList").append(html);
	} else {
		$("#ImgPreviewList",parent.document).html(html);
	}
}