
function showCustResult( result, url , deletePerm , photoPerm , editPerm , littlePerm) {
    search.style.display = 'contents';
    base.style.display = 'none';
    if ( result.length == 0 ) {
        search.innerHTML = "<tr><td colspan = '10' class='text-center p-20'>موردی یافت نشد</td></tr>";
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
            td_row.innerHTML = result[i].id;
            
            var td_fullname = tr.appendChild( document.createElement( 'td' ) );
            var a_customer = td_fullname.appendChild(document.createElement('a'));
            a_customer.setAttribute('href' , url + 'deal/profile/' + result[i].customer_id);
            a_customer.setAttribute('target' , '_blank');
            a_customer.setAttribute('class' , 'enterCustomer');
            a_customer.innerHTML = result[i].fullname;
            
            var td_type = tr.appendChild( document.createElement('td'));
            if(result[i].type == 1){var type = 'خرید';}else{var type = 'فروش';}
            td_type.innerHTML = type;
            
            var td_count = tr.appendChild( document.createElement( 'td' ) );
            td_count.innerHTML = numeral(result[i].count_money).format('0,0') + '</br>' + result[i].name;
            
            var td_convert = tr.appendChild(document.createElement('td'));
            td_convert.innerHTML = numeral(result[i].convert).format('0,0');
            
            var td_volume = tr.appendChild(document.createElement('td'));
            td_volume.setAttribute('class' , 'lright');
            if(Number(result[i].volume) < Number(result[i].pay)){
                td_volume.classList.add('text-danger');
            }
            var span_volume = td_volume.appendChild(document.createElement('span'));
            span_volume.setAttribute('title' , ' ( '+ numeral(result[i].count_money).format('0,0') + ' + ' + numeral(result[i].wage).format('0,0') + ' ) × ' + numeral(result[i].convert).format('0,0') );
            span_volume.setAttribute('data-toggle' , 'tooltip');
            span_volume.innerHTML = numeral(result[i].volume).format('0,0');
            
            var td_pay = tr.appendChild(document.createElement('td'));
            td_pay.innerHTML = numeral(result[i].pay).format('0,0');
            td_pay.setAttribute('class' , 'lright');
            if(Number(result[i].volume) < Number(result[i].pay)){
                td_pay.classList.add('text-danger');
            }

            var td_rest = tr.appendChild(document.createElement('td'));
            td_rest.setAttribute('class' , 'lright');
            if(Number(result[i].rest) < Number(0)){
                td_rest.classList.add('text-danger');
            }
            var span_rest = td_rest.appendChild(document.createElement('span'));
            span_rest.setAttribute('title' , numeral(result[i].volume).format('0,0') + ' - ' + numeral(result[i].pay).format('0,0') );
            span_rest.setAttribute('data-toggle' , 'tooltip');
            span_rest.innerHTML = numeral(result[i].rest).format('0,0');

            var td_date = tr.appendChild(document.createElement('td'));
            td_date.innerHTML = result[i].date_deal + '</br>' + result[i].time_deal;
            
            var td_tool = tr.appendChild( document.createElement( 'td' ) );
            td_tool.setAttribute( 'class', 'text-center' );

            var ul_tool = td_tool.appendChild( document.createElement( 'ul' ) );
            ul_tool.setAttribute( 'class', 'icons-list' );
               
            var li_settings = ul_tool.appendChild(document.createElement('li'));
            li_settings.setAttribute('class' , 'dropdown');
            li_settings.setAttribute('title' , 'تنظیمات');
            li_settings.setAttribute('data-toggle' , 'tooltip');
            var a_settings = li_settings.appendChild(document.createElement('a'));
            a_settings.setAttribute('class' , 'dropdown-toggle');
            a_settings.setAttribute('data-toggle' , 'dropdown');
            var i_settings = a_settings.appendChild(document.createElement('i'));
            i_settings.setAttribute('class' , 'icon-cog7');
            var ul_settings = li_settings.appendChild(document.createElement('ul'));
            ul_settings.setAttribute('class' , 'dropdown-menu dropdown-menu-right');
            var li1 = ul_settings.appendChild(document.createElement('li'));
            var a1 = li1.appendChild(document.createElement('a'));
            a1.innerHTML = 'نمایش در داشبورد';
            var li2 = ul_settings.appendChild(document.createElement('li'));
            var a2 = li2.appendChild(document.createElement('a'));
            a2.innerHTML = 'عدم نمایش در داشبورد';
            li1.setAttribute('onclick' , 'settings(this)');
            li2.setAttribute('onclick' , 'settings(this)');

            var checkLittle = Math.abs(result[i].volume - result[i].pay);
            if(littlePerm && result[i].pay != 0 && result[i].rest != 0 && checkLittle != 0 && Number(checkLittle) <= Number(50000)){
            var li_little = ul_tool.appendChild(document.createElement('li'));
            li_little.setAttribute( 'class', "text-blue-800" );
            li_little.setAttribute('title' , 'پرداخت خرد');
            li_little.setAttribute('data-toggle' , 'tooltip');
            var a_little = li_little.appendChild(document.createElement('a'));
            a_little.setAttribute('onclick' , 'payLittle('+result[i].id + ','+checkLittle + ')');
            var i_little = a_little.appendChild(document.createElement('i'));
            i_little.setAttribute('class' , 'icon-stack-up');
            }

            if(editPerm && result[i].state == 1){
            var li_edit = ul_tool.appendChild( document.createElement( 'li' ) );
            li_edit.setAttribute( 'class', "text-primary" );
            li_edit.setAttribute('title' , 'ویرایش معامله ');
            li_edit.setAttribute('data-toggle' , 'tooltip');
            var a_edit = li_edit.appendChild( document.createElement( 'a' ) )
            a_edit.setAttribute( 'href', url + "deal/edit/"+ result[ i ].id );
            var i_edit = a_edit.appendChild( document.createElement( 'i' ) );
            i_edit.setAttribute( 'class', 'icon-pencil6' );
            }

            if(photoPerm){
                var li_photo = ul_tool.appendChild( document.createElement( 'li' ) );
                li_photo.setAttribute( 'class', "text-indigo-600" );
                li_photo.setAttribute('title' , 'مشاهده قبض');
                li_photo.setAttribute('data-toggle' , 'tooltip');
                var a_photo = li_photo.appendChild( document.createElement( 'a' ) )
                a_photo.setAttribute( 'href', url + "deal/photo/" + result[ i ].id );
                var i_photo = a_photo.appendChild( document.createElement( 'i' ) );
                i_photo.setAttribute( 'class', 'icon-stack-picture' );
            }

            if(deletePerm && result[i].state == 1){
            var li_delete = ul_tool.appendChild( document.createElement( 'li' ) );
            li_delete.setAttribute( 'class', "text-danger-600" );
            li_delete.setAttribute( 'title', "حذف معامله" );
            li_delete.setAttribute('data-toggle' , 'tooltip');
            var a_delete = li_delete.appendChild( document.createElement( 'a' ) )
            a_delete.setAttribute( 'href', "#modal_theme_danger" );
            a_delete.setAttribute('data-toggle' , 'modal');
            var i_delete = a_delete.appendChild( document.createElement( 'i' ) );
            i_delete.setAttribute( 'class', 'icon-trash' );
            i_delete.setAttribute('onclick' , 'deleteDeal('+ result[i].id + ','+result[i].pay+')');
            }
        }
       search.replaceChild( div, search.firstChild );
    }
    $(function () {
        $('[data-toggle="tooltip"]').tooltip();
    })
}
//show and dont show
function settings(elem){
    var color = elem.parentElement.parentElement;
    if(elem.nextElementSibling == null){
        elem.previousElementSibling.removeAttribute("style");
    }else{
        elem.nextElementSibling.removeAttribute("style");
    }
    if(elem.style.backgroundColor == 'rgb(153, 255, 153)'){
        elem.removeAttribute("style");
        color.removeAttribute("style");

    }else{
        color.style.color=" #e65c00";
        elem.style.backgroundColor = '#99ff99';
    }
}
//show and dont show