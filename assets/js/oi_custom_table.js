function js_num_format(val) {
	var res = val.toLocaleString(undefined, {minimumFractionDigits: 2,maximumFractionDigits: 2});
	return res;
}	
	
function js_alpha_only(str) {
	var res = str.trim();
	if (str.match(/[a-z]/i)) {
		res = str.replace(/[^a-z .\/]+/ig, "");
		res = str.replace(/[\/]+/ig, ", ");
	}
	return res.trim();
}	

function js_alpha_seo(str) {
	var res = str.trim();
	if (str.match(/[a-z]/i)) {
		res = str.replace(/[^a-z]+/ig, "");
	}
	return res.trim();
}

function js_clean_title(str) {
	var res = str.trim();
	res = str.replace(/[^a-z0-9 .-\/]+/ig, " "); 	
	return res.toUpperCase().trim();
}



jQuery(document).ready(function($) {
	
/* ============================================================================== 
/*	@@ GENERATE DATATABLE
/* ------------------------------------------------------------------------------ */
	
	$.makeTable = function (mycdata, mytitle, mylevel) { 
		
		let cols_not_allow = ['hakuna', 'record_id', 'post_code'];
		
		
		let cols_numeric = [ 'latitude', 'longitude', 'percentage', 'amount'];

		//console.log("arr_include", cols_allow.includes('Erlich'));
		//console.log("mycdata", Object.keys(mycdata[0]));
		
		if(mycdata !== null)
		{
			
 
			var pclass = (mycdata[0].category_id !== undefined) ? mycdata[0].category_id : (mycdata[0].parent_id !== undefined) ? mycdata[0].parent_id : '';

			//if(mytitle !== '') { mytitle = '<caption>&nbsp;</caption>'; }
			var table = $('<div class="table-responsivex"><table class="table table-striped table-responsive display piedata tblev_'+mylevel+'">' + mytitle + '');
			var tblHeader = "<thead><tr>";		
			
			var table_b = '<div class="table-responsivex"><table class="table table-striped table-responsive display tblev_'+mylevel+'" id="dt_example">';
			
			var tblTotalBudget = 0;
			var tblTotalExpend = 0;
			var tblTotalRevenue = 0; 
			var lebo; var numAlign;


			for (var k in mycdata[0]) {  
				lebo = k; numAlign = ""; 
				if(k == 'name') { lebo = "Title"; }
				if(k == 'vbudget') 	{ lebo = "Budgeted "; }  


				numAlign = (cols_numeric.includes(k)) ? " class='txtright' " : "";

				if(!cols_not_allow.includes(k))
				{	
					tblHeader += "<th nowrap "+numAlign+">" + js_clean_title(lebo) + "</th>"; /*lebo.toLocaleUpperCase()*/
				}
			}
			tblHeader += "</tr></thead>";			

			//$(tblHeader).appendTo(table);
			
			var tblBody  = "<tbody>";
			
			$.each(mycdata, function (index, value) { 
				/*console.log("index ", index + " --- value: "+ Object.keys(value));*/
				/*console.log("value", value);*/
				/*console.log("values", Object.keys(value).length);*/

				if(Object.keys(value).length > 0)
				{
					var dtr_id = (value.drilldown !== undefined) ? js_alpha_seo(value.drilldown) : js_alpha_seo(value.record_id);
					var dtr_title = (value.drilldown !== undefined) ?  value.drilldown :  (value.title !== undefined) ?  value.title :  dtr_id;

					var TableRow = "<tr class='dtcontrol dtlev_"+mylevel+"' data-level='"+mylevel+"' data-title='"+dtr_title+"' data-id='"+dtr_id+"' id='tr_"+dtr_id+"'>"; /*<td><img alt='+'></td>*/
					$.each(value, function (key, val) { 

						numAlign = (cols_numeric.includes(key)) ? " txtright " : "";
						val = (cols_numeric.includes(key)) ? val.toLocaleString() : val;

						//if(key !== 'drilldown' && key !== 'y' && key !== 'vperc' && key !== 'record_id') 
						if(!cols_not_allow.includes(key))	
						{


							if(key == 'vbudget') { numAlign = " txtright "; tblTotalBudget = tblTotalBudget + val; val = js_num_format(val);  }
							if(key == 'vexpend') { numAlign = " txtright "; tblTotalExpend = tblTotalExpend + val; val = js_num_format(val);  }
							if(key == 'vrevenue') { numAlign = " txtright "; tblTotalRevenue = tblTotalRevenue + val; val = js_num_format(val);  }
							if(key == 'actual_revenue') { numAlign = " txtright "; tblTotalRevenue = tblTotalRevenue + val; val = js_num_format(val);  }

							if(key == 'percentage' || key == 'percentage_allocation' || key == 'percentage_exchequer' || key == 'absorption') { 
								//val = js_num_format(val);
								//val = val.toLocaleString();


								//if(pclass == 'Expenditure'){
									if(val > 100 || val < 50){ numAlign = " txtright txtred "; }
									else { numAlign = " txtright txtgreen "; }
								//}
								  val = val + '%';  
							}

							if(key == 'name') { val = js_alpha_only(val); }						
							TableRow += "<td class='"+numAlign+"'>"+ val + "</td>";
						}
					});
					TableRow += "</tr>";
					//$(table).append(TableRow);
					tblBody += TableRow;
				}
			});

			tblBody += "</tbody></table>";
			table_b += tblHeader + tblBody;	 

			//return ($(table));
			return (table_b);
		}
	};	
	
	
});