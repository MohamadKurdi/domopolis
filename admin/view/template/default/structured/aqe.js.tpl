<script>
!function(a){var b=function(b,c){this.$element=a(b),this.options=a.extend({},a.fn.button.defaults,c)};b.prototype.setState=function(a){var b="disabled",c=this.$element,d=c.data(),e=c.is("input")?"val":"html";a+="Text",d.resetText||c.data("resetText",c[e]()),c[e](d[a]||this.options[a]),setTimeout(function(){a=="loadingText"?c.addClass(b).attr(b,b):c.removeClass(b).removeAttr(b)},0)},b.prototype.toggle=function(){var a=this.$element.closest('[data-toggle="buttons-radio"]');a&&a.find(".active").removeClass("active"),this.$element.toggleClass("active")};var c=a.fn.button;a.fn.button=function(c){return this.each(function(){var d=a(this),e=d.data("button"),f=typeof c=="object"&&c;e||d.data("button",e=new b(this,f)),c=="toggle"?e.toggle():c&&e.setState(c)})},a.fn.button.defaults={loadingText:"loading..."},a.fn.button.Constructor=b,a.fn.button.noConflict=function(){return a.fn.button=c,this},a(document).on("click.button.data-api","[data-toggle^=button]",function(b){var c=a(b.target);c.hasClass("btn")||(c=c.closest(".btn")),c.button("toggle")})}(window.jQuery)

var t;

(function($){
    var _old = $.unique;
    $.unique = function(arr){
        if (!(arr instanceof Array)||!arr.length||!!arr[0].nodeType){
            return _old.apply(this,arguments);
        } else {
            // reduce the array to contain no dupes via grep/inArray
            return $.grep(arr,function(v,k){
                return $.inArray(v,arr) === k;
            });
        }
    };

    $.fn.serializeHash = function(){
        var hash = {};
        $.each($(this).serializeArray(), function(i, obj){
            if (hash[obj.name] == undefined)
                hash[obj.name] = obj.value;
            else if (typeof hash[obj.name] == "object")
                hash[obj.name].push(obj.value);
            else hash[obj.name] = [hash[obj.name], obj.value];
        });
        return hash;
    }

    $(document).ready(function() {
        if ($("div.warning").length == 0) {
            var e = $('<div/>').addClass("warning").fadeTo(0, 0).hide();
            $('div[class^="breadcrumb"]').after(e);
            e.data("bgc", e.css("backgroundColor"));
        }
        if ($("div.success").length == 0) {
            var e = $('<div/>').addClass("success").fadeTo(0, 0).hide();
            $('div[class^="breadcrumb"]').after(e)
            e.data("bgc", e.css("backgroundColor"));
        }
    });

    $.editable.addInputType('popup_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr({'type':'hidden'});
            $(this).append(input);
            return(input);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            $(original).append(original.revert)
            var form = this;
            if (json.error) {
                show_message(json.error, $("#content"), true);
                $('input', this).remove();
                t = setTimeout(function() {
                    original.reset(form);
                }, 0);
                return;
            } else {
                $(original).editable('disable');
                aqe_popup(json.title, json.popup, function(done_callback) {
                    data = {id: $(original).attr('id'), old: "", new: ""}
                    $.extend(data, $("#aqe-popup-form").serializeHash())
                    aqe_popup_update(settings.updateurl, data, function(d) {
                      if (d === true || d.success) {
                        var val = (typeof d.value != 'undefined') ? d.value : '';
                        $(original).html(val).css('width', '')
                        original.editing = false;
                        if (settings.tooltip) {
                            $(original).attr('title', settings.tooltip);
                        }
                        $(original).editable('enable');
                      } else if (d.errors) {
                        $("span.error", $("#aqe-popup-form")).remove()
                        $.each(d.errors, function(index, value) {
                            if ($("[name='" + index + "']").length, $("#aqe-popup-form")) {
                                $("[name='" + index + "']").parent().append($("<span/>", {"class":"error"}).html(value));
                            }
                        })
                      }
                      if ($.isFunction(done_callback)) {
                        done_callback.call(null, d);
                      }
                    });
                }, function () {
                    $(original).editable('enable');
                    original.reset(form);
                })
            }
        }
    });

    $.editable.addInputType('multiselect_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr({'type':'hidden'});
            $(this).append(input);
            return(input);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            $(original).append(original.revert)
            var form = this;
            if (json.error) {
                show_message(json.error, $("#content"), true);
                $('input', this).remove();
                t = setTimeout(function() {
                    original.reset(form);
                }, 0);
                return;
            } else {
                aqe_popup(json.title, json.popup, function(done_callback) {
                    form.submit();
                    if ($.isFunction(done_callback)) {
                        done_callback.call(null, true);
                    }
                }, function () {
                    original.reset(form);
                }, 1000, 700)
            }
        }
    });

    $.editable.addInputType('image_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr({'type':'hidden', 'id': 'img-' + $(original.revert).attr('data-id'), 'value':$(original.revert).attr('data-image')});
            $(this).append(input);
            return(input);
        },
        content : function(data, settings, original) {
            $(this).append($(original.revert))
            var form = this;
            update_image('img-' + $(original.revert).attr('data-id'), function() {
                form.submit();
            })
        }
    });

    $.editable.addInputType('multilingual_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr('autocomplete','off');
            $(this).append(input);
            return(input);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            var form = this;
            if (json.error) {
                show_message(json.error, $("#content"), true);
                $('input', this).remove();
                t = setTimeout(function() {
                    original.reset(form);
                }, 0);
                return;
            }
            $(original).width($(original).width())
            if (json.hasOwnProperty('languages')) {
                var select = $('<select />');
                for (var key in json.languages) {
                    if ('selected' == key) {
                        continue;
                    } 
                    var option = $('<option />').val(key).append(json.languages[key]).attr("title", json.data[key]);
                    select.append(option);
                }
                if (settings.width != 'none') {
                    tester = $('<div style="position:absolute; top:0px; left:-1000px; visibility:hidden;"/>').append(select.clone());
                    tester.appendTo("body");
                    select_width = $("select:first", tester).outerWidth();
                    if (settings.width - settings.padding - select_width < 148)
                        width = settings.width - settings.padding;
                    else
                        width = settings.width - settings.padding - select_width;
                    $('input', this).css({'width': width + 'px', 'padding-top': 0, 'padding-bottom': 0, 'margin-top': '-1px', 'margin-bottom': '-1px'});
                    tester.remove();
                }
                $(this).prepend(select);
            }
            /* Loop option again to set selected. IE needed this... */ 
            $('select', this).children().each(function() {
                if ($(this).val() == json.languages['selected'] || 
                    $(this).text() == $.trim(original.revert)) {
                        $(this).attr('selected', 'selected');
                }
            });
            $('input:first', this).val(json.data[json.languages['selected']]);
            $('select', this).change(function() {
                var option = this.options[this.selectedIndex];
                $('input:first', form).val(option.title);
            });
        }
    });

    $.editable.addInputType('product_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr('autocomplete','off').attr("id", 'product_edit');
            if (settings.width != 'none') { input.css({'width': settings.width - settings.padding + 'px', 'padding-top': 0, 'padding-bottom': 0, 'margin-top': '-1px', 'margin-bottom': '-1px'}); }
            $(this).append(input);
            input.autocomplete({
                delay: 100,
                source: function(request, response) {
                    $.ajax({
                        url: 'index.php?route=catalog/product/autocomplete&token=' + settings.token + '&filter_name=' + encodeURIComponent(request.term),
                        dataType: 'json',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    label: item.name,
                                    value: item.product_id
                                }
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    $('input[id=\'product_edit\']').val(ui.item.label);
                    $('input[id=\'product_edit\']').attr('title', ui.item.value);
                    return false;
                },
                focus: function(event, ui) {
                    return false;
                }
            });
            return(input);
        }
    });

    $.editable.addInputType('customer_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            input.attr('autocomplete','off').attr("id", 'customer_edit');
            if (settings.width != 'none') { input.css({'width': settings.width - settings.padding + 'px', 'padding-top': 0, 'padding-bottom': 0, 'margin-top': '-1px', 'margin-bottom': '-1px'}); }
            $(this).append(input);
            input.autocomplete({
                delay: 100,
                source: function(request, response) {
                    $.ajax({
                        url: 'index.php?route=sale/customer/autocomplete&token=' + settings.token + '&filter_name=' + encodeURIComponent(request.term),
                        dataType: 'json',
                        success: function(json) {
                            response($.map(json, function(item) {
                                return {
                                    category: item.customer_group,
                                    label: item.name,
                                    value: item.customer_id
                                }
                            }));
                        }
                    });
                },
                select: function(event, ui) {
                    $("input[id='customer_edit']").val(ui.item.label);
                    $("input[id='customer_edit']").attr('title', ui.item.value);
                    return false;
                },
                focus: function(event, ui) {
                    return false;
                }
            });
            return(input);
        }
    });

    $.editable.addInputType('status_edit', {
        /* create input element */
        element : function(settings, original) {
            var select = $('<select />');
            $(this).append(select);
            return(select);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            for (var key in json) {
                if (!json.hasOwnProperty(key)) {
                    continue;
                }
                if ('selected' == key) {
                    continue;
                }
                var option = $('<option />').val(key).append(json[key]);
                $('select', this).append(option);
            }
            var label = $('<label />');
            label.attr("for", "notify");
            label.html(settings.notify_customer_text);
            var checkbox = $('<input />');
            var notify = settings.notify_customer;
            checkbox.attr({"type":"checkbox", "name":"notify", "id":"notify", "value":"1"});
            if (notify)
                checkbox.attr("checked", "checked");
            $(this).append($('<br />')).append(checkbox).append(label);
            /* Put explicitly focus on the checkbox. Chrome/Safari need this... */
            checkbox.click(function(){
                $(this).focus();
            });
            /* Loop option again to set selected. IE needed this... */
            $('select', this).children().each(function() {
                if ($(this).val() == json['selected'] ||
                    $(this).text() == $.trim(original.revert)) {
                        $(this).attr('selected', 'selected');
                }
            });
            var form = this;
            /* Submit on change if no submit button defined. */
            if (!settings.submit) {
                $('select', this).change(function() {
                    form.submit();
                });
            }
        }
    });

    $.editable.addInputType('advanced_select_edit', {
        /* create input element */
        element : function(settings, original) {
            var select = $('<select />');
            $(this).append(select);
            return(select);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            if (json.error) {
                show_message(json.error, $("#content"), true);
                $('input', this).remove();
                t = setTimeout(function() {
                    original.reset(form);
                }, 0);
                return;
            }
            json = json.select;
            for (var key in json) {
                if (!json.hasOwnProperty(key)) {
                    continue;
                }
                if ('selected' == key) {
                    continue;
                }
                var option = $('<option />').val(key).append(json[key]);
                $('select', this).append(option);
            }
            /* Loop option again to set selected. IE needed this... */
            $('select', this).children().each(function() {
                if ($(this).val() == json['selected'] ||
                    $(this).text() == $.trim(original.revert)) {
                        $(this).attr('selected', 'selected');
                }
            });
            var form = this;
            /* Submit on change if no submit button defined. */
            if (!settings.submit) {
                $('select', this).change(function() {
                    form.submit();
                });
            }
        }
    });

    $.editable.addInputType('date_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            if (settings.width != 'none') { input.css({'width': settings.width - settings.padding / 2 - 18 + 'px', 'padding-top': 0, 'padding-bottom': 0, 'margin-top': '-1px', 'margin-bottom': '-1px'}); }
            input.attr('autocomplete','off');
            $(this).append(input);
            input.datepicker({
                dateFormat: 'yy-mm-dd',
                showOn: "button",
                buttonImage: "view/javascript/ui/themes/smoothness/images/calendar.gif",
                buttonImageOnly: true,
                onClose: function(dateText, inst) {
                    inst.input.focus();
                }
            });
            return(input);
        }
    });

    $.editable.addInputType('date_time_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            if (settings.width != 'none') { input.css({'width': settings.width - settings.padding / 2 - 16 + 'px', 'padding-top': 0, 'padding-bottom': 0, 'margin-top': '-1px', 'margin-bottom': '-1px'}); }
            input.attr('autocomplete','off');
            $(this).append(input);
            input.datetimepicker({
                dateFormat: 'yy-mm-dd',
                timeFormat: 'hh:mm',
                showOn: "button",
                buttonImage: "view/javascript/ui/themes/smoothness/images/calendar.gif",
                buttonImageOnly: true,
                onClose: function(dateText, inst) {
                    inst.input.focus();
                }
            });
            return(input);
        }
    });

    $.editable.addInputType('person_name_edit', {
        /* create input element */
        element : function(settings, original) {
            var input = $('<input />');
            var span = $('<span />');
            span.html("First name:"); // TODO: translate
            input.attr({'autocomplete':'off', 'id':'first_name'});
            $(this).append(input).prepend(span);
            return(input);
        },
        content : function(data, settings, original) {
            var t;
            /* If it is string assume it is json. */
            if (String == data.constructor) {
                eval ('var json = ' + data);
            } else {
            /* Otherwise assume it is a hash already. */
                var json = data;
            }
            var form = this;
            if (json.error) {
                show_message(json.error, $("#content"), true);
                $('input', this).remove();
                t = setTimeout(function() {
                    original.reset(form);
                }, 0);
                return;
            }
            $(original).width($(original).width())
            $("input:first", this).val(json.first_name);
            var last_name = $('<input />');
            var span = $('<span />');
            var br = $('<br />');
            span.html("Last name:");
            last_name.attr({'autocomplete':'off', 'id':'last_name'}).val(json.last_name);
            $(this).append(br).append(span).append(last_name);
            $(":input", form).each(function() {
                $(this).keydown(function(e) {
                    if (e.keyCode == 13) {
                        form.submit();
                    }
                });
            });
        }
    });

})(jQuery);

function extractFieldAndId(str) {
  const regex = /^(.+)-(\d+)$/;  
  const match = str.match(regex);
  
  if (match) {
    const field_id      = match[1];
    const product_id    = parseInt(match[2]);
    
    return { field_id, product_id };
  } else {
    return null;
  }
}

function quick_update(el, value, settings, update_url, params) {
    var $           = jQuery;
    var ret_val     = el.revert;
    var elem        = $(el);
    var elem_id     = $(el).attr("id");
    var data        = {id : elem_id, old : ret_val, new: value};

    if (params) {
        $.extend(data, params);
    }

    elem.editable('disable');
    aqe_popup_update(update_url, data, function(data) {
        if (data && data.success) {
            ret_val = data.value;
        }
        elem.html(ret_val).css('width', '').editable('enable');
    });

    var field = extractFieldAndId($(el).attr("id"));
    console.log($(el).attr("id"));
    console.log(field);

    $('input[name="selected[]"]:checked').each(function() {
        var selected_product_id = $(this).val();

        if (field && parseInt(selected_product_id) != parseInt(field.product_id)){            
            var next_data        = {id : selected_product_id, old : ret_val, new: value};            
            var next_elem        = $('#' + field.field_id + '-' + selected_product_id);            
            var next_data        = {id : $(next_elem).attr("id"), old : ret_val, new: value};

            next_elem.editable('disable');
            aqe_popup_update(update_url, next_data, function(next_data) {
                if (next_data && next_data.success) {
                    ret_val = next_data.value;
                }
                next_elem.html(ret_val).css('width', '').editable('enable');
            });
        }
    });


    return settings.indicator;
}

function aqe_popup_update(update_url, params, callback) {
    var $ = jQuery;
    var data = {};
    if (params) {
        $.extend(data, params);
    }
    $.ajax({
        type: 'POST',
        url: update_url,
        dataType: 'json',
        data: data,
        success: function(data, status, xhr) {
            if (data.error) {
                show_message(data.error, $("#content"), true);
            }
            if (data.success) {
                show_message(data.success, $("#content"));
            }
            if ($.isFunction(callback)) {
                callback.call(null, data);
            }
        },
        error : function(xhr, status, error) {
            show_message(status, $("#content"), true, error);
            if ($.isFunction(callback)) {
                callback.call(null, status);
            }
        }
    });
}

function update_cell_value(url, ref) {
    var $cell = $("#" + ref),
        w = $cell.innerWidth(),
        h = $cell.innerHeight(),
        val = "";
    $.ajax({
        type: 'POST',
        url: url,
        dataType: 'json',
        data: {id: ref},
        beforeSend: function() {
            $cell.css({'padding': 0, 'width': w + 'px', 'height': h + 'px', 'position':'relative'}).html(
                $("<div/>", {"class":"overlay-container"}).append(
                    $("<div/>", {"class":"overlay"}),
                    $("<img/>", {"src":"view/image/aqe-pro/aqe_image_loading.gif", "class":"overlay-loading-spinner"})
                )
           );
           $cell.editable('disable');
        },
        success: function(data, status, xhr) {
            if (data.error) {
                show_message(data.error, $("#content"), true);
            }
            if (data.success && data.value) {
                val = data.value;
            }
        },
        error: function(xhr, status, error) {
            show_message(status, $("#content"), true, error);
        },
        complete: function(xhr, status) {
            $cell.empty().removeAttr("style");
            setTimeout(function() {
                $cell.html(val).editable('enable');
            }, 0);
        }
    });
}

function load_popup_data(loadurl, params, callback) {
    var $ = jQuery;
    loaddata = {};
    if (params) {
        $.extend(loaddata, params);
    }
    $.ajax({
        type : "POST",
        url  : loadurl,
        data : loaddata,
        dataType: "json",
        success: function(data, status, xhr) {
            if ($.isFunction(callback)) {
                callback.call(null, data);
            }
        },
        error: function(xhr, status, error) {
            show_message(status, $("#content"), true, error);
        }
    });
}

function remove_notice($notice, speed, chain, done_callback) {
    var $ = jQuery;
    if (chain) {
        $notice.fadeTo(speed / 2, 0.0, function() {
            $(this).hide();
            if ($.isFunction(done_callback)) {
                done_callback.call();
            }
        });
    } else {
        $notice.fadeTo(speed / 2, 0.0, function() {
            $(this).slideUp(speed / 2, function() {
                if ($.isFunction(done_callback)) {
                    done_callback.call();
                }
            });
        });
    }
}

function show_notice($notice, speed, chain, done_callback) {
    var $ = jQuery;
    if (chain) {
        $notice.show().fadeTo(speed / 2, 1.0, function() {
            if ($.isFunction(done_callback)) {
                done_callback.call();
            }
        });
    } else {
        $notice.slideDown(speed / 2, function() {
            $(this).fadeTo(speed / 2, 1.0, function() {
                if ($.isFunction(done_callback)) {
                    done_callback.call();
                }
            });
        });
    }
}

function swap_notice($from, $to, msg, speed, done_callback) {
    var $ = jQuery;
    if ($from.is(':visible')) {
        remove_notice($from, speed, true, function(){
            show_notice($to.html(msg), speed, true, function() {
                if ($.isFunction(done_callback)) {
                    done_callback.call();
                }
            });
        });
    } else if ($to.is(':visible')) {
        if ($to.data("bgc") == "undefined") {
            $to.data("bgc", $to.css("backgroundColor"));
        }
        $to.animate({backgroundColor: "#FFFFFF"}, 200, "easeOutQuad", function() {
            $(this).html(msg).animate({backgroundColor: $to.data("bgc")}, 200, "easeInQuad");
        });
    } else {
        show_notice($to.html(msg), speed, false, function() {
            if ($.isFunction(done_callback)) {
                done_callback.call();
            }
        });
    }
}

function show_message(msg, parent, error, exception) {
    var $ = jQuery;
    if (!msg)
        return;
    if (t)
        clearTimeout(t);
    error = typeof error !== 'undefined' ? error : false;
    exception = typeof exception !== 'undefined' ? exception : "";
    if (error) {
        swap_notice($("div.success", parent), $("div.warning", parent), msg, 800);
        if (console && console.log && exception) {
            console.log(exception);
        }
    } else {
        swap_notice($("div.warning", parent), $("div.success", parent), msg, 800);
        t = setTimeout(function(){
            remove_notice($("div.success", parent), 800, false);
        }, 10000);
    }
}

function aqe_popup(title, content, callback_save, callback_cancel, width, height) {
    var $ = jQuery,
        width = typeof width !== 'undefined' ? width : Math.min(screen.width, 950),
        height = typeof height !== 'undefined' ? height : Math.min(screen.height, 800),
        cancel = true;
    $('#aqe-dialog').remove();

    $('#content').prepend($('<div />', {id: "aqe-dialog", style: "padding: 3px 0px 0px 0px;"}));

    $('#aqe-dialog').dialog({
        title: title,
        bgiframe: false,
        width: width,
        minWidth: Math.min(screen.width, 600),
        maxWidth: screen.width,
        maxHeight: screen.height,
        height: height,
        resizable: true,
        modal: true,
        buttons: [
          {
            text : "Save",
            click: function () {
              $dialog = $(this);
              $("#save-overlay").stop().fadeTo(300, 0.8);
              if ($.isFunction(callback_save)) {
                if (typeof CKEDITOR != "undefined" && CKEDITOR.instances) {
                    for (var inst in CKEDITOR.instances) {
                      CKEDITOR.instances[inst].updateElement();
                    }
                }
                callback_save.call(null, function(data) {
                  if (data === true || data.success) {
                    cancel = false;
                    $dialog.dialog("close");
                    $dialog.remove();
                  } else {
                    $("#save-overlay").stop().fadeOut(300);
                    if (data.error) {
                      $popup = $("#aqe-popup");
                      show_message(data.error, $popup, true);
                    }
                  }
                }) ;
              } else {
                $dialog.dialog("close");
                $dialog.remove();
              }
            }
          },
          {
            text : "Cancel",
            click: function () {
              cancel = false;
              $(this).dialog("close");
              if ($.isFunction(callback_cancel)) {
                callback_cancel.call();
              }
              $(this).remove();
            }
          }
        ],
        open: function(event, ui) {
          $('#aqe-dialog').append(content);
          $popup = $("#aqe-popup");
          if ($("div.warning", $popup).length == 0) {
            var e = $('<div/>').addClass("warning").fadeTo(0, 0).hide();
            $("div.notice-container", $popup).append(e)
            e.data("bgc", e.css("backgroundColor"));
          }
          if ($("div.success", $popup).length == 0) {
            var e = $('<div/>').addClass("success").fadeTo(0, 0).hide();
            $("div.notice-container", $popup).append(e)
            e.data("bgc", e.css("backgroundColor"));
          }
        },
        close: function(event, ui) {
          if (typeof CKEDITOR != "undefined" && CKEDITOR.instances) {
            for (var inst in CKEDITOR.instances) {
              CKEDITOR.instances[inst].destroy(true);
            }
          }
          if (cancel) {
              cancel = false;
              if ($.isFunction(callback_cancel)) {
                callback_cancel.call();
              }
          }
        }
    });
};

</script>