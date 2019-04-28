
function showCustResult( result, url ) {
    search.style.display = 'contents';
    base.style.display = 'none';
    if ( result.length == 0 ) {
        search.innerHTML = "<tr><td colspan = '11' class='text-center p-20'>موردی یافت نشد</td></tr>";
        return;
    } else {
        var div = document.createElement( 'tbody' );
        var len = result.length;
        for ( var i = 0; i < len ; i++ ) {
            var tr = div.appendChild( document.createElement( 'tr' ) );
            if(result[i].state == 0){
                tr.style.backgroundColor = '#dad3f8';
            }
            var td_row = tr.appendChild( document.createElement( 'td' ) );
            td_row.innerHTML = Number(result[i].id) + Number(100);
            
            var td_fullname = tr.appendChild( document.createElement( 'td' ) );
            var a_customer = td_fullname.appendChild(document.createElement('a'));
            a_customer.setAttribute('href' , url + 'deal/handle_profile/' + result[i].cust_id);
            a_customer.setAttribute('target' , '_blank');
            a_customer.innerHTML = result[i].fullname;
            
            var td_type = tr.appendChild( document.createElement('td'));
            if(result[i].type == 1){var type = 'خرید';}else{var type = 'فروش';}
            td_type.innerHTML = type;
            
            var td_count = tr.appendChild( document.createElement( 'td' ) );
            td_count.innerHTML = numeral(result[i].count_money).format('0,0') + ' ' + result[i].name;
            
            var td_convert = tr.appendChild(document.createElement('td'));
            td_convert.innerHTML = numeral(result[i].convert).format('0,0');
            
            var td_volume = tr.appendChild(document.createElement('td'));
            td_volume.innerHTML = numeral(result[i].volume).format('0,0');
            
            var td_pay = tr.appendChild(document.createElement('td'));
            td_pay.innerHTML = numeral(result[i].pay).format('0,0');
            
            var td_rest = tr.appendChild(document.createElement('td'));
            td_rest.innerHTML = numeral(result[i].rest).format('0,0');
            if(result[i].pay > result[i].volume){
                td_pay.setAttribute('class' , 'text-danger');
                td_volume.setAttribute('class' , 'text-danger');
            }
            if(result[i].rest < 0){
                td_rest.setAttribute('class' , 'text-danger');
            }
            var td_date = tr.appendChild(document.createElement('td'));
            td_date.innerHTML = result[i].date_deal + '</br>' + result[i].time_deal;

            var td_modify = tr.appendChild(document.createElement('td'));
                td_modify.innerHTML = result[i].date_modified;
            
            var td_tool = tr.appendChild( document.createElement( 'td' ) );
            td_tool.setAttribute( 'class', 'text-center' );

            var ul_tool = td_tool.appendChild( document.createElement( 'ul' ) );
            ul_tool.setAttribute( 'class', 'icons-list' );
            
            var li_edit = ul_tool.appendChild( document.createElement( 'li' ) );
            li_edit.setAttribute( 'class', "text-primary" );
            li_edit.setAttribute('title' , 'ویرایش معامله ');
            var a_edit = li_edit.appendChild( document.createElement( 'a' ) )
            a_edit.setAttribute( 'href', url + "deal/edit/"+ result[ i ].id );
            var i_edit = a_edit.appendChild( document.createElement( 'i' ) );
            i_edit.setAttribute( 'class', 'icon-pencil6' );

            
            var li_photo = ul_tool.appendChild( document.createElement( 'li' ) );
            li_photo.setAttribute( 'class', "text-indigo-600" );
            li_photo.setAttribute('title' , 'مشاهده قبض');
            var a_photo = li_photo.appendChild( document.createElement( 'a' ) )
            a_photo.setAttribute( 'href', url + "deal/photo/" + result[ i ].id );
            var i_photo = a_photo.appendChild( document.createElement( 'i' ) );
            i_photo.setAttribute( 'class', 'icon-stack-picture' );

            var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
            li_delete.setAttribute( 'class', "text-danger-600" );
            li_delete.setAttribute( 'title', "حذف معامله" );
            var a_delete = li_delete.appendChild( document.createElement( 'a' ) )
            a_delete.setAttribute( 'href', "#modal_theme_danger" );
            a_delete.setAttribute('data-toggle' , 'modal');
            var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
            i_delete.setAttribute( 'class', 'icon-trash' );
            i_delete.setAttribute('onclick' , 'deleteDeal('+ result[i].id + ','+result[i].pay+')');
        }
       search.replaceChild( div, search.firstChild );
    }
}