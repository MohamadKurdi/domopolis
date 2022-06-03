var availableDomains = {
    base: _base_translate
};

function _translate(b, g, e) {
    if ("object" != typeof g) {
        g = {}
    }
    if ("undefined" == typeof e) {
        e = "base"
    }
    var c = b.split(".");
    var a = availableDomains[e];
    for (var d in c) {
        if ("undefined" === typeof a[c[d]]) {
            return b
        }
        a = a[c[d]]
    }
    var f = a;
    for (var d in g) {
        f = f.replace(new RegExp("%" + d + "%", "g"), g[d])
    }
    return f
}

function _transchoice(d, c, h, f) {
    if ("object" != typeof h) {
        h = {}
    }
    h.count = d;
    var g = _translate(c, h, f);
    if (g === c) {
        return c
    }
    var b = g.split("|");
    var a = globalSettings.locale.substr(0, 2);
    switch (a) {
        case "ru":
            var e = ((d % 10 == 1) && (d % 100 != 11)) ? 0 : (((d % 10 >= 2) && (d % 10 <= 4) && ((d % 100 < 10) || (d % 100 >= 20))) ? 1 : 2);
            return b[e];
            break;
        case "en":
            var e = (d == 1) ? 0 : 1;
            return b[e];
            break;
        default:
            break
    }
}

function _getDateFormatString(a) {
    if (typeof a === "undefined" || !a) {
        var a = false
    }
    switch (globalSettings.locale) {
        case "ru_RU":
            return a ? "dd.mm" : "dd.mm.yy";
        case "en_GB":
            return a ? "dd.mm" : "dd/mm/yy";
        default:
            return a ? "dd.mm" : "dd.mm.yy"
    }
}

function localeAwareNumberFormat(d, c) {
    if ("undefined" === typeof c) {
        if (globalSettings.orderProductQuantityIsFractional) {
            c = 3
        } else {
            c = 0
        }
    }
    var b = Number(d).toFixed(c).toString();
    switch (globalSettings.locale) {
        case "ru_RU":
            var a = ",";
            break;
        case "en_GB":
            var a = ".";
            break;
        default:
            var a = ".";
            break
    }
    return b.replace(".", a)
};
var AdminModule = (function() {
    var b = {};
    var d = function(f) {
        for (var g in f) {
            return f[g]
        }
        return undefined
    };
    var a = function(f) {
        if (f.find("tbody tr").length == 0) {
            f.addClass("hide")
        } else {
            f.removeClass("hide")
        }
    };
    var c = function() {
        var f = $("#intaro_crmbundle_deliverytypetype_integrationDeliveryCode").val();
        var g = $("#section-main").data("integrations");
        if (f != "" && g[f] != undefined && g[f]["hasPaymentConfiguration"]) {
            $(".delivery-price-table__list_net_cost").hide();
            $(".delivery-price-table__list_net_cost input").val(0)
        }
    };

    function e(h, g, f) {
        this.$popup = h;
        this.$view = g;
        this.$form = f;
        this.popupData = {};
        this.matrix = [];
        this.loader = '<div class="overpage o-bg black-red-loader" style="position: absolute; height: 100%"></div>';
        this.regionLoadingProcess;
        this.regionNameFilterTimeout;
        this.cityIsLoading = false;
        this.cityPage = 1;
        this.cityTotalPage;
        this.cityNameFilterTimeout;
        this.init = function() {
            var i = this;
            this.renderCostMatrix();
            this.$popup.find("a.close").bind("beforeClose", function(j) {
                if (!i.validate()) {
                    i.$popup.find("#tab-delivery-price-popup-price").click();
                    if (!confirm(i.$popup.find("#section-delivery-price-popup-price").data("warning"))) {
                        j.stopPropagation()
                    }
                }
            });
            $(".delivery-price__popup-btn, .delivery-price-table__row").live("click", function(j) {
                j.preventDefault();
                i.openPopup($(this))
            });
            this.$popup.find("a.delivery-price__save-btn").click(function(j) {
                j.preventDefault();
                if (!i.validate()) {
                    return false
                }
                i.save();
                $("#delivery-price-popup a.close").click()
            });
            if (this.$popup.is(".without-save-btn")) {
                this.$popup.find("a.close").click(function(j) {
                    i.save()
                })
            }
            $('#section-delivery-price-popup-area ul.scroll-select__country-list input[type = "radio"]').change(function() {
                i.updateRegionList($(this).val())
            });
            $('#section-delivery-price-popup-area ul.scroll-select__region-list input[type = "radio"]').live("change", function() {
                i.cityPage = 1;
                i.updateCityList($(this).val(), true)
            });
            $("#section-delivery-price-popup-area ul.scroll-select__city-list").parent().scroll(function() {
                $(this).children(".black-red-loader").css("margin-top", $(this).scrollTop());
                if ($(this)[0].scrollHeight - ($(this).scrollTop() + $(this).height()) - 100 > 0 || i.cityIsLoading) {
                    return
                }
                i.cityIsLoading = true;
                i.cityPage += 1;
                var j = i.$popup.find('.scroll-select__region-list input[type = "radio"]:checked').val();
                if (j != undefined && i.cityPage <= i.cityTotalPage) {
                    i.updateCityList(j, false)
                }
                $(this).children(".black-red-loader").css("margin-top", $(this).scrollTop())
            });
            $("#section-delivery-price-popup-area .scroll-select__head input.scroll-select__region-filter").bind("input", function() {
                clearTimeout(i.regionNameFilterTimeout);
                var j = $(this);
                i.regionNameFilterTimeout = setTimeout(function() {
                    var k = j.val().toUpperCase();
                    var l = i.$popup.find('.scroll-select__region-list li input[id != "region-list-all"]');
                    l.each(function() {
                        var m = $(this).siblings("label").text().substring(0, k.length).toUpperCase();
                        if (k == "" || m == k) {
                            $(this).parent().show()
                        } else {
                            $(this).parent().hide()
                        }
                    })
                }, 250)
            });
            $("#section-delivery-price-popup-area .scroll-select__head input.scroll-select__city-list").bind("input", function() {
                clearTimeout(i.cityNameFilterTimeout);
                var j = i.$popup.find('.scroll-select__region-list input[type = "radio"]:checked').val();
                if (j != undefined) {
                    i.cityNameFilterTimeout = setTimeout(function() {
                        i.cityPage = 1;
                        i.updateCityList(j, true)
                    }, 500)
                }
            });
            $("ul.scroll-select__city-list input").live("change", function() {
                if ($(this).is(":checked")) {
                    if ($(this).attr("id") == "city-list-all") {
                        $(this).parents("ul").find("input:checked:not(:first)").removeAttr("checked")
                    } else {
                        $("#city-list-all").removeAttr("checked")
                    }
                }
                i.updateSelectedList($(this))
            })
        };
        this.openPopup = function(i) {
            this.popupData = {
                location: {}
            };
            if (i.data("matrix-index") != undefined) {
                this.$popup.data("matrix-index", i.data("matrix-index"));
                if (this.matrix[this.$popup.data("matrix-index")] != undefined) {
                    this.popupData = JSON.parse(JSON.stringify(this.matrix[this.$popup.data("matrix-index")]))
                }
            } else {
                this.$popup.data("matrix-index", -1)
            }
            this.updateRegionList("");
            this.$popup.find(".scroll-select__country-list :checked").removeAttr("checked");
            this.updateSelectedList();
            this.$popup.intaroPopup();
            $("#tab-delivery-price-popup-area").click()
        };
        this.validate = function() {
            return true
        };
        this.save = function() {
            var i = this.$popup.data("matrix-index");
            if (i == -1) {
                this.matrix.push(this.popupData)
            } else {
                if (this.matrix[i] != undefined) {
                    this.matrix[i] = this.popupData
                }
            }
            this.$form.val(JSON.stringify(this.matrix));
            this.renderCostMatrix()
        };
        this.renderCostMatrix = function() {
            this.matrix = JSON.parse(this.$form.val());
            var s = this.$view.find("tbody");
            s.find("tr").remove();
            if (getLength(this.matrix) == 0 && this.$view.data("empty-template")) {
                s.append(this.$view.data("empty-template"))
            }
            for (var n = 0; n < getLength(this.matrix); n++) {
                if (this.matrix[n] == undefined) {
                    continue
                }
                var q = $(this.$view.data("template"));
                q.data("matrix-index", n);
                for (var j in this.matrix[n].location) {
                    $countryItem = $(q.data("country-prototype"));
                    $countryItem.find("span").text(this.$view.data("countries")[this.matrix[n].location[j].id]);
                    q.find(".delivery-price_region__list").append($countryItem);
                    if (getLength(this.matrix[n].location[j].region) == 0) {
                        continue
                    }
                    var p = Object.values(this.matrix[n].location[j].region).sort(function(t, i) {
                        return (t.name < i.name) ? -1 : 1
                    });
                    for (var r in p) {
                        var m = $(q.data("region-prototype"));
                        m.find("span").text(p[r].name);
                        if (getLength(p[r].city) > 0) {
                            var l = Object.values(p[r].city).sort(function(t, i) {
                                return (t.name < i.name) ? -1 : 1
                            });
                            var k = $('<ul class="delivery-price_region__list_ins"></ul>');
                            for (var o in l) {
                                k.append('<li class="delivery-price_region__item">' + (l[o].id == "" ? this.$view.data("all-cities") : l[o].name) + "</li>")
                            }
                            m.append(k)
                        }
                        q.find(".delivery-price_region__list").append(m)
                    }
                }
                q = this.renderMatrixRow(q, this.matrix[n]);
                s.append(q)
            }
            a(this.$view)
        };
        this.renderMatrixRow = function(i, j) {
            return i
        };
        this.updateRegionList = function(m) {
            if (this.regionLoadingProcess) {
                this.regionLoadingProcess.abort()
            }
            var n = $("#section-delivery-price-popup-area ul.scroll-select__region-list");
            n.find("li:not(:first)").remove();
            n.find("li input").removeAttr("checked");
            var j = $("#section-delivery-price-popup-area ul.scroll-select__city-list");
            j.find("li:not(:first)").remove();
            $("#city-list-all").parent().hide();
            if (m == "") {
                $("#region-list-all").parent().hide();
                return
            }
            $("#region-list-all").parent().show().removeClass("scroll-select__label_checked");
            if (this.popupData.location[m] != undefined && this.popupData.location[m].region[""] != undefined && this.popupData.location[m].region[""].city.length > 0) {
                $("#region-list-all").parent().addClass("scroll-select__label_checked")
            }
            if (globalSettings.geohelper == undefined || globalSettings.geohelper.useAutocomplete) {
                var l = n.find("#region-list-all").parent().clone();
                n.after(this.loader);
                var k = {
                    "filter[countryIso]": m,
                    "pagination[limit]": 100
                };
                var i = this;
                this.regionLoadingProcess = $.ajax({
                    url: Routing.generate("crm_dictionary_geohelper_region"),
                    data: k,
                    dataType: "json",
                    success: function(q) {
                        if (q.result == undefined) {
                            alert(_translate("error.request_error_try_later"));
                            return
                        }
                        for (var p = 0; p < q.result.length; p++) {
                            var o = l.clone();
                            if (o.hasClass("scroll-select__label_checked")) {
                                o.addClass("scroll-select__label_disabled");
                                o.find("input").attr("disabled", "disabled")
                            }
                            o.find("input").attr("id", "region-list-" + q.result[p].id).attr("value", q.result[p].id);
                            o.find("label").attr("for", "region-list-" + q.result[p].id).text(q.result[p].name);
                            if (i.popupData.location[m] != undefined && i.popupData.location[m].region[q.result[p].id] != undefined && i.popupData.location[m].region[q.result[p].id].city.length > 0) {
                                o.addClass("scroll-select__label_checked")
                            }
                            n.append(o)
                        }
                        $("#section-delivery-price-popup-area .scroll-select__head input.scroll-select__region-filter").trigger("input")
                    },
                    complete: function() {
                        n.siblings(".black-red-loader").remove()
                    }
                })
            }
        };
        this.updateCityList = function(p, j) {
            var o = this.$popup.find("ul.scroll-select__country-list :checked");
            var r = $("#section-delivery-price-popup-area ul.scroll-select__city-list");
            var l = r.find("#city-list-all").parent();
            var k = $("#section-delivery-price-popup-area .scroll-select__head input.scroll-select__city-list");
            if (j) {
                r.find("li:not(:first)").remove()
            }
            l.show().find("input").removeAttr("checked");
            if (p == "" || p == undefined) {
                if (this.popupData.location[o.val()] != undefined && this.popupData.location[o.val()].region[p] != undefined) {
                    for (var m = 0; m < this.popupData.location[o.val()].region[p].city.length; m++) {
                        if (this.popupData.location[o.val()].region[p].city[m].id == "") {
                            l.find("input").attr("checked", "checked")
                        }
                    }
                }
                return
            }
            if (globalSettings.geohelper == undefined || globalSettings.geohelper.useAutocomplete) {
                var n = l.clone();
                r.after(this.loader);
                var q = {
                    "locale[lang]": globalSettings.locale.substr(0, 2),
                    "filter[regionId]": p,
                    "pagination[page]": this.cityPage,
                    "pagination[limit]": 100
                };
                if (k.val() != "") {
                    q["filter[name]"] = k.val()
                }
                var s = this;
                $.get(Routing.generate("crm_dictionary_geohelper_city"), q).success(function(x) {
                    if (x.result == undefined) {
                        alert(_translate("error.request_error_try_later"));
                        return
                    }
                    s.cityTotalPage = x.pagination.totalPageCount;
                    var v = [];
                    var u = false;
                    if (s.popupData.location[o.val()] != undefined && s.popupData.location[o.val()].region[p] != undefined) {
                        for (var w = 0; w < s.popupData.location[o.val()].region[p].city.length; w++) {
                            if (s.popupData.location[o.val()].region[p].city[w].id == "") {
                                u = true;
                                break
                            }
                            v.push(s.popupData.location[o.val()].region[p].city[w].id)
                        }
                    }
                    if (u) {
                        l.find("input").attr("checked", "checked")
                    }
                    for (var w = 0; w < x.result.length; w++) {
                        var t = n.clone();
                        t.find("input").attr("id", "city-list-" + x.result[w].id).attr("value", x.result[w].id);
                        t.find("label").attr("for", "city-list-" + x.result[w].id).text(x.result[w].name);
                        if (x.result[w].area != undefined) {
                            t.find("label").append('<span class="scroll-select__discr">' + x.result[w].area + "</span>")
                        }
                        if (!u && v.indexOf(x.result[w].id.toString()) != -1) {
                            t.find("input").attr("checked", "checked")
                        }
                        r.append(t)
                    }
                }).always(function() {
                    s.cityIsLoading = false;
                    r.siblings(".black-red-loader").remove()
                })
            }
        };
        this.updateSelectedList = function(k) {
            if (k != undefined) {
                var u = this.$popup.find("ul.scroll-select__country-list :checked");
                if (u.length == 0) {
                    return
                }
                if (this.popupData.location[u.val()] == undefined) {
                    this.popupData.location[u.val()] = {
                        id: u.val(),
                        name: u.siblings("label").text().trim(),
                        region: {}
                    }
                }
                var n = this.$popup.find("ul.scroll-select__region-list :checked");
                if (this.popupData.location[u.val()].region[n.val()] == undefined) {
                    this.popupData.location[u.val()].region[n.val()] = {
                        id: n.val(),
                        name: n.siblings("label").text().trim(),
                        city: []
                    }
                }
                if (this.popupData.location[u.val()].region[n.val()].city == undefined || (k.val() == "" && k.is(":checked"))) {
                    this.popupData.location[u.val()].region[n.val()].city = []
                }
                var m = false;
                for (var p = 0; p < this.popupData.location[u.val()].region[n.val()].city.length; p++) {
                    if (k.val() == this.popupData.location[u.val()].region[n.val()].city[p].id) {
                        m = true;
                        if (!k.is(":checked")) {
                            this.popupData.location[u.val()].region[n.val()].city.splice(p, 1);
                            p--;
                            continue
                        }
                    }
                    if (this.popupData.location[u.val()].region[n.val()].city[p].id == "" && k.is(":checked")) {
                        this.popupData.location[u.val()].region[n.val()].city.splice(p, 1)
                    }
                }
                if (k.is(":checked") && !m) {
                    this.popupData.location[u.val()].region[n.val()].city.push({
                        id: k.val(),
                        name: k.siblings("label").contents().filter(function() {
                            return this.nodeType == 3
                        })[0].nodeValue
                    })
                }
                if (this.popupData.location[u.val()] != undefined && this.popupData.location[u.val()].region[n.val()] != undefined && this.popupData.location[u.val()].region[n.val()].city.length > 0) {
                    n.parent().addClass("scroll-select__label_checked")
                } else {
                    n.parent().removeClass("scroll-select__label_checked")
                }
                if (this.popupData.location[u.val()].region[n.val()].city.length == 0) {
                    delete this.popupData.location[u.val()].region[n.val()]
                }
                if (Object.keys(this.popupData.location[u.val()].region).length == 0) {
                    delete this.popupData.location[u.val()]
                }
                if (n.val() == "" && k.val() == "") {
                    var w = this.$popup.find("ul.scroll-select__region-list li");
                    w = w.filter(function(i) {
                        return $("#region-list-all", this).length != 1
                    });
                    if (k.is(":checked")) {
                        w.addClass("scroll-select__label_disabled").addClass("scroll-select__label_checked").find("input").attr("disabled", "disabled")
                    } else {
                        w.removeClass("scroll-select__label_disabled").removeClass("scroll-select__label_checked").find("input").removeAttr("disabled")
                    }
                }
            }
            var o = 0;
            var q = this.$popup.find(".scroll-select__stats");
            this.$popup.find(".scroll-select__country-list li").removeClass("scroll-select__label_checked");
            var r = q.children("ul");
            r.children().remove();
            var v = true;
            for (var s in this.popupData.location) {
                var j = $("<div>" + r.data("country-prototype") + "</div>");
                j.find("span").text(this.popupData.location[s].name);
                var t = true;
                for (var x in this.popupData.location[s].region) {
                    if (this.popupData.location[s].region[x].city.length == 0) {
                        continue
                    }
                    t = false;
                    var l = $(r.data("region-prototype"));
                    l.children("span").text(this.popupData.location[s].region[x].name);
                    if (this.popupData.location[s].region[x].city.length == 1 && this.popupData.location[s].region[x].city[0] == "") {
                        l.children("ul").remove()
                    } else {
                        for (var p = 0; p < this.popupData.location[s].region[x].city.length; p++) {
                            if (this.popupData.location[s].region[x].city[p].id != "") {
                                $cityItem = $(r.data("city-prototype"));
                                $cityItem.text(this.popupData.location[s].region[x].city[p].name);
                                l.children("ul").append($cityItem)
                            }
                            o++
                        }
                    }
                    j.append(l)
                }
                if (!t) {
                    if (!v && !j.siblings("br").length) {
                        j.prepend("<br>")
                    } else {
                        j.children("br").remove()
                    }
                    r.append(j.children());
                    v = false;
                    this.$popup.find('.scroll-select__country-list input[value="' + s + '"]').parent().addClass("scroll-select__label_checked")
                }
            }
            q.find("a.scroll-select__drop-toggle").text(_transchoice(o, "info.selected_regions", {
                "%count%": o
            }));
            if (o == 0) {
                q.find("a.scroll-select__drop-toggle").siblings("ul.scroll-select__drop-block").hide()
            } else {
                q.find("a.scroll-select__drop-toggle").siblings("ul.scroll-select__drop-block").removeAttr("style")
            }
        }
    }
    b.initPriceTypeEdit = function() {
        var f = $("#delivery-type-regionweight-conditions .delivery-price-table");
        var g = new e($("#delivery-price-popup"), f, $("#delivery-type-regionweight-conditions .delivery-price__cost-data"));
        g.init();
        if ($("#section-main").data("is-default")) {
            $("#tab-application_rules").parent().hide()
        } else {
            $("#tab-application_rules").parent().show()
        }
        $("a.price-type__popup-btn").click(function(h) {
            h.preventDefault();
            f.find(".delivery-price-table__row").click()
        });
        $(".delivery-price-table span.tr-delete").live("click", function(h) {
            h.stopPropagation();
            g.popupData = {
                location: {}
            };
            $("#delivery-price-popup").data("matrix-index", 0);
            g.save();
            g.$view.addClass("hide")
        })
    };
    b.initDeliveryTypeEdit = function() {
        function g(m, l) {
            e.apply(this, arguments);
            var i = this.init;
            this.init = function() {
                i.call(this);
                var n = this;
                this.$popup.find(".delivery-price-btns a.delivery-price__add-btn").click(function(o) {
                    o.preventDefault();
                    addCollectionFormRow(n.$popup.find(".delivery-price-table"));
                    c()
                });
                this.$popup.find("#section-delivery-price-popup-price table.delivery-price-table").delegate('select[name $= "[netValueType]"]', "change", function() {
                    var o = $(this).parents("td").next().next();
                    if ($(this).val() == "subtract_percent") {
                        o.text("%")
                    } else {
                        o.html(globalSettings.currencyHtml)
                    }
                });
                $(".delivery-price-table span.tr-delete").live("click", function(q) {
                    q.stopPropagation();
                    var p = $(this).parents("tr");
                    var o = $(this).parents("table");
                    if (p.data("matrix-index") != undefined && n.matrix[p.data("matrix-index")] != undefined) {
                        n.matrix.splice(p.data("matrix-index"), 1);
                        n.save()
                    }
                    p.remove();
                    a(o)
                })
            };
            var k = this.openPopup;
            this.openPopup = function(o) {
                k.apply(this, arguments);
                var q = $("#intaro_crmbundle_deliverytypetype_availableCountries input:checked").map(function() {
                    return $(this).val()
                }).get();
                this.$popup.find(".scroll-select__country-list li").each(function() {
                    if (q.indexOf($(this).find("input").val()) == -1) {
                        $(this).hide()
                    } else {
                        $(this).show()
                    }
                });
                if (this.popupData.cost == undefined) {
                    this.popupData.cost = []
                }
                var n = $("#section-delivery-price-popup-price .delivery-price-table");
                n.find("tr:not(:first)").remove();
                if (this.popupData.cost.length > 0) {
                    for (var p = 0; p < this.popupData.cost.length; p++) {
                        $row = addCollectionFormRow(n);
                        if (this.popupData.cost[p].weightStart !== null) {
                            $row.find('input[name $= "[weightStart]"]').val(this.popupData.cost[p].weightStart)
                        }
                        if (this.popupData.cost[p].weightEnd !== null) {
                            $row.find('input[name $= "[weightEnd]"]').val(this.popupData.cost[p].weightEnd)
                        }
                        if (this.popupData.cost[p].summStart !== null) {
                            $row.find('input[name $= "[summStart]"]').val(this.popupData.cost[p].summStart.replace(/,/, "."))
                        }
                        if (this.popupData.cost[p].summEnd !== null) {
                            $row.find('input[name $= "[summEnd]"]').val(this.popupData.cost[p].summEnd.replace(/,/, "."))
                        }
                        $row.find('select[name $= "[netValueType]"]').val(this.popupData.cost[p].netValueType);
                        $row.find('input[name $= "[netValue]"]').val(this.popupData.cost[p].netValue);
                        $row.find('input[name $= "[value]"]').val(this.popupData.cost[p].value)
                    }
                } else {
                    addCollectionFormRow(n)
                }
                this.$popup.find('#section-delivery-price-popup-price select[name $= "[netValueType]"]').change();
                c()
            };
            this.validate = function() {
                var A = {
                    empty: false,
                    overlapped: false,
                    wrongOrder: false
                };
                var n = this.$popup.find(".delivery-price-table tr:not(:first)");
                n.find(".delivery-price-table__val_error").removeClass("delivery-price-table__val_error");
                this.$popup.find(".delivery-price-btns .msg-error").addClass("hide");
                var w = [];
                for (var u = 0; u < n.length; u++) {
                    if ($(n[u]).find('input[name $= "[weightStart]"]').val() == "" && $(n[u]).find('input[name $= "[weightEnd]"]').val() == "" && $(n[u]).find('input[name $= "[summStart]"]').val() == "" && $(n[u]).find('input[name $= "[summEnd]"]').val() == "") {
                        A.empty = true;
                        $(n[u]).find('input[name $= "[weightStart]"]').addClass("delivery-price-table__val_error");
                        $(n[u]).find('input[name $= "[weightEnd]"]').addClass("delivery-price-table__val_error");
                        $(n[u]).find('input[name $= "[summStart]"]').addClass("delivery-price-table__val_error");
                        $(n[u]).find('input[name $= "[summEnd]"]').addClass("delivery-price-table__val_error")
                    } else {
                        var z = $(n[u]).find('input[name $= "[weightStart]"], input[name $= "[weightEnd]"]');
                        var p = $(n[u]).find('input[name $= "[weightStart]"]').val();
                        var s = $(n[u]).find('input[name $= "[weightEnd]"]').val();
                        var t = $(n[u]).find('input[name $= "[summStart]"], input[name $= "[summEnd]"]');
                        var q = $(n[u]).find('input[name $= "[summStart]"]').val();
                        var B = $(n[u]).find('input[name $= "[summEnd]"]').val();
                        var y = [p == "" ? 0 : parseInt(p), s == "" ? Infinity : parseInt(s), q == "" ? 0 : parseFloat(q), B == "" ? Infinity : parseFloat(B)];
                        if (y[0] > y[1]) {
                            A.wrongOrder = true;
                            z.addClass("delivery-price-table__val_error")
                        }
                        if (y[2] > y[3]) {
                            A.wrongOrder = true;
                            t.addClass("delivery-price-table__val_error")
                        }
                        for (var v = 0; v < w.length; v++) {
                            var x = false;
                            var o = false;
                            if (y[0] >= w[v][0] && y[1] <= w[v][1]) {
                                x = true
                            } else {
                                if (y[0] <= w[v][0] && y[1] >= w[v][1]) {
                                    x = true
                                } else {
                                    if (y[0] <= w[v][0] && y[1] >= w[v][0]) {
                                        x = true
                                    } else {
                                        if (y[0] <= w[v][1] && y[1] >= w[v][0]) {
                                            x = true
                                        }
                                    }
                                }
                            }
                            if (y[2] >= w[v][2] && y[3] <= w[v][3]) {
                                o = true
                            } else {
                                if (y[2] <= w[v][2] && y[3] >= w[v][3]) {
                                    o = true
                                } else {
                                    if (y[2] <= w[v][2] && y[3] >= w[v][2]) {
                                        o = true
                                    } else {
                                        if (y[2] <= w[v][3] && y[3] >= w[v][2]) {
                                            o = true
                                        }
                                    }
                                }
                            }
                            if (x && o) {
                                A.overlapped = true;
                                if (y[0] != 0 || y[1] != Infinity) {
                                    z.addClass("delivery-price-table__val_error")
                                }
                                if (y[2] != 0 || y[3] != Infinity) {
                                    t.addClass("delivery-price-table__val_error")
                                }
                            }
                        }
                        w.push(y)
                    }
                    if ($(n[u]).find('input[name $= "[netValue]"]').val() == "") {
                        A.empty = true;
                        $(n[u]).find('input[name $= "[netValue]"]').addClass("delivery-price-table__val_error")
                    }
                    if ($(n[u]).find('input[name $= "[value]"]').val() == "") {
                        A.empty = true;
                        $(n[u]).find('input[name $= "[value]"]').addClass("delivery-price-table__val_error")
                    }
                }
                var r = [];
                if (A.empty) {
                    r.push(_translate("message.need_set_fields"))
                }
                if (A.overlapped) {
                    r.push(_translate("message.range_has_intersections"))
                }
                if (A.wrongOrder) {
                    r.push(_translate("message.range_start_bigger_end"))
                }
                if (r.length > 0) {
                    this.$popup.find(".msg-error").text(r.join(". ") + ".").removeClass("hide");
                    return false
                }
                return true
            };
            var h = this.save;
            this.save = function() {
                var o = this.$popup.find(".delivery-price-table tr:not(:first)");
                this.popupData.cost = [];
                for (var n = 0; n < o.length; n++) {
                    this.popupData.cost.push({
                        weightStart: $(o[n]).find('input[name $= "[weightStart]"]').val(),
                        weightEnd: $(o[n]).find('input[name $= "[weightEnd]"]').val(),
                        summStart: $(o[n]).find('input[name $= "[summStart]"]').val(),
                        summEnd: $(o[n]).find('input[name $= "[summEnd]"]').val(),
                        netValueType: $(o[n]).find('select[name $= "[netValueType]"]').val(),
                        netValue: $(o[n]).find('input[name $= "[netValue]"]').val(),
                        value: $(o[n]).find('input[name $= "[value]"]').val()
                    })
                }
                h.call(this)
            };
            var j = this.renderCostMatrix;
            this.renderCostMatrix = function() {
                j.call(this);
                c()
            };
            this.renderMatrixRow = function(n, p) {
                var r = $(n.data("item-prototype"));
                for (var o = 0; o < p.cost.length; o++) {
                    $weightItem = $(n.data("weight-prototype")).clone();
                    var s = "";
                    if (p.cost[o].weightEnd == "") {
                        $weightItem.find("span:first").text(">");
                        $weightItem.find("span").eq(2).text(p.cost[o].weightStart);
                        $weightItem.find("span").eq(1).remove()
                    } else {
                        if (p.cost[o].weightStart == "") {
                            $weightItem.find("span:first").text("<");
                            $weightItem.find("span").eq(2).text(p.cost[o].weightEnd);
                            $weightItem.find("span").eq(1).remove()
                        } else {
                            $weightItem.find("span:first").text(p.cost[o].weightStart);
                            $weightItem.find("span").eq(2).text(p.cost[o].weightEnd)
                        }
                    }
                    n.find(".delivery-price-table__list_weight").append($weightItem);
                    $summItem = $(n.data("summ-prototype")).clone();
                    var q = "";
                    $summItem.find("span").show();
                    if (p.cost[o].summEnd == "" && p.cost[o].summStart == "") {
                        $summItem.find("span").hide()
                    } else {
                        if (p.cost[o].summEnd == "") {
                            $summItem.find("span:first").text(">");
                            $summItem.find("span").eq(2).text(p.cost[o].summStart);
                            $summItem.find("span").eq(1).remove()
                        } else {
                            if (p.cost[o].summStart == "") {
                                $summItem.find("span:first").text("<");
                                $summItem.find("span").eq(2).text(p.cost[o].summEnd);
                                $summItem.find("span").eq(1).remove()
                            } else {
                                $summItem.find("span:first").text(p.cost[o].summStart);
                                $summItem.find("span").eq(2).text(p.cost[o].summEnd)
                            }
                        }
                    }
                    n.find(".delivery-price-table__list_summ").append($summItem);
                    cost = parseFloat(p.cost[o].value);
                    netCost = parseFloat(p.cost[o].netValue);
                    if (p.cost[o].netValueType == "subtract") {
                        netCost = cost - netCost
                    } else {
                        if (p.cost[o].netValueType == "subtract_percent") {
                            netCost = cost - cost * (netCost / 100)
                        }
                    }
                    $item = r.clone();
                    $item.find("span").html(priceFormat(netCost));
                    n.find(".delivery-price-table__list_net_cost ul").append($item);
                    $item = r.clone();
                    $item.find("span").html(priceFormat(p.cost[o].value));
                    n.find(".delivery-price-table__list_cost").append($item)
                }
                return n
            }
        }
        var f = new g($("#delivery-price-popup"), $("#delivery-type-regionweight-conditions .delivery-price-table"), $("#delivery-type-regionweight-conditions .delivery-price__cost-data"));
        f.init();
        $(".s-tabs a").click(function(h) {
            jsTabs($(this), h)
        });
        $("#main").find("form").submit(function() {
            $("#delivery-services").find("tr.service").each(function() {
                var h = $(this).find("td.name input").val();
                var i = $(this).find("td.code input").val();
                if (i === "" && h === "") {
                    $(this).remove()
                }
            });
            return true
        });
        $('input[name="intaro_crmbundle_deliverytypetype[availableCountries][]"]').change(function() {
            var h = $('input[name="intaro_crmbundle_deliverytypetype[availableCountries][]"]:checked');
            h = h.map(function() {
                return $(this).val()
            }).toArray();
            filter_vat_rates_by_country(h)
        }).change();
        $("#intaro_crmbundle_deliverytypetype_integrationModule").change(function() {
            var j = $(this).val();
            var h = $("#delivery-services").parents(".control-group");
            var i = $("#intaro_crmbundle_deliverytypetype_availableCountries");
            if (j) {
                h.hide();
                if (j != "courier") {
                    i.hide()
                } else {
                    i.show()
                }
            } else {
                h.show();
                i.show()
            }
        }).change();
        $('#intaro_crmbundle_deliverytypetype_dynamicCostCalculation input[type="radio"]').change(function() {
            if (!$(this).is(":checked")) {
                return
            }
            if ($(this).val() == "0") {
                $(".delivery-price__dynamic-configuration").hide()
            } else {
                $(".delivery-price__dynamic-configuration").show()
            }
        }).change();
        $("#intaro_crmbundle_deliverytypetype_costDependsOnRegionWeight").change(function() {
            if ($(this).is(":checked")) {
                $("#delivery-type-regionweight-conditions").show()
            } else {
                $("#delivery-type-regionweight-conditions").hide()
            }
        }).change();
        $("#intaro_crmbundle_deliverytypetype_costDependsOnDateTime").change(function() {
            if ($(this).is(":checked")) {
                $("#delivery-type-datetime-conditions").show()
            } else {
                $("#delivery-type-datetime-conditions").hide()
            }
        }).change();
        $("a.delivery-price__add-condition-btn").click(function(j) {
            j.preventDefault();
            var i = $(this).siblings("table");
            if (!i.length) {
                return false
            }
            var h = addCollectionFormRow(i);
            a(i);
            $(".timepicker").timepicker();
            c()
        });
        a($("#delivery-type-datetime-conditions table.delivery-price-table"));
        $("#delivery-type-link-copy-cost-conditions").click(function(h) {
            h.preventDefault();
            $popup = $("#delivery-type-copy-popup");
            $popup.intaroPopup()
        });
        $("#delivery-type-btn-copy-cost").click(function(h) {
            h.preventDefault();
            if ($("#delivery_type_copy_source").val() == "") {
                $popup.find(".msg-error").text(_translate("message.need_set_delivery_type")).removeClass("hide");
                $("#delivery_type_copy_source").one("change", function() {
                    $popup.find(".msg-error").addClass("hide")
                });
                return
            }
            $button = $(this);
            $button.attr("disabled", true);
            $popup = $("#delivery-type-copy-popup");
            $popup.find(".msg-error").addClass("hide");
            $.post($(this).data("url"), {
                sourceId: $("#delivery_type_copy_source").val()
            }).success(function(i) {
                if (i.success) {
                    location.reload()
                } else {
                    var j;
                    if (i.message != undefined) {
                        j = i.message
                    } else {
                        j = _translate("alert.request_error_try_later")
                    }
                    $popup.find(".msg-error").text(j).removeClass("hide");
                    $button.attr("disabled", false)
                }
            }).error(function() {
                $popup.find(".msg-error").text(_translate("alert.request_error_try_later")).removeClass("hide");
                $button.attr("disabled", false)
            })
        })
    };
    return b
})();
$(document).ready(function() {
    var i = $("table.table-delivery-services"),
        e = $("table.table-integration-api-keys"),
        a = $("table.table-delivery-time-range"),
        d = $("table.table-product-filter"),
        f = $("table.telephony-missing-code"),
        h = $("table.external_phones"),
        g = $("table.telphin-queue-code");
    i.data("index", i.find("tbody tr").length - 1);
    e.data("index", e.find("tbody tr").length - 1);
    a.data("index", a.find("tbody tr").length - 1);
    d.data("index", d.find("tbody tr").length - 1);
    f.data("index", f.find("tbody tr").length - 1);
    h.data("index", h.find("tbody tr").length - 1);
    g.data("index", g.find("tbody tr").length - 1);

    function b(m, o) {
        var l = m.data("prototype"),
            k = m.data("index"),
            n = $(l.replace(/__name__/g, k));
        m.data("index", k + 1);
        m.find("tbody tr.append").before(n);
        if (typeof o !== "undefined") {
            o(n)
        }
        $("thead", m).show()
    }

    function c(l) {
        var k = [];
        $(l).each(function(n, m) {
            k.push($(m).val())
        });
        $("option", l).each(function(m, n) {
            var o = $(n);
            if (o.val() && !o.attr("selected") && $.inArray(o.val(), k) > -1) {
                o.attr("disabled", "disabled")
            } else {
                o.removeAttr("disabled")
            }
        });
        $("select:not(.hide)", d).trigger("refresh").trigger("chosen:updated")
    }
    $("#add-delivery-service-btn").click(function() {
        b(i);
        return false
    });
    $("#add-delivery-time-range-btn").click(function() {
        b(a, function(k) {
            k.find("input").mask("99:99")
        });
        return false
    });
    $("#add-external-phones-btn").click(function() {
        b(h);
        h.initJsControls();
        return false
    });
    $("#add-missing-code-btn").click(function() {
        b(f);
        return false
    });
    $("#add-multiple-qeue-code-btn").click(function() {
        b(g);
        return false
    });
    $("table.table-delivery-time-range input").mask("99:99");
    $("#add-partner-key-btn").click(function() {
        b(e);
        e.initJsControls();
        return false
    });
    $(".add-http-action-param-btn").live("click", function() {
        var k = $(this).parents(".control-group");
        $table = $("table.table-action-http-params", k);
        $table.data("index", $table.find("tbody tr").length - 1);
        b($table, function(l) {
            var m = l.find(".ace-editor");
            if (m.length) {
                initAceEditor(m)
            }
        });
        return false
    });
    $("#add-product-filter-btn").click(function() {
        b(d);
        c($("tr .prop-field", d));
        var k = $(".prop-field:first option").length - 1;
        if (d.data("index") >= k) {
            $(this).hide()
        }
        d.initJsControls();
        return false
    });
    $("tr .prop-field", d).live("change", function() {
        var m = $(this);
        var l = m.parents("tr");
        var k = $(".prop-type", l);
        var n = m.data("allowedTypes")[m.val()];
        $("option", k).each(function(o, p) {
            var q = $(p);
            if (q.val() && $.inArray(q.val(), n) == -1) {
                q.attr("disabled", "disabled")
            } else {
                q.removeAttr("disabled")
            }
        });
        k.trigger("refresh").trigger("chosen:update");
        c($("tr .prop-field", d))
    });
    $("tr .prop-field", d).trigger("change");
    $(".table-product-filter .tr-delete").live("click", function() {
        $(this).parents("tr").remove();
        d.data("index", d.find("tbody tr").length - 1);
        c($("tr .prop-field", d));
        $("#add-product-filter-btn").show();
        return false
    });
    var j = $("select[id$=configuration_serialization]");
    j.live("change", function() {
        var k = $(this).parents(".action-event-item");
        if ($(this).val() == "raw") {
            $("textarea[id$=configuration_rawBody]", k).parents(".control-group").show();
            $("table.table-body.table-action-http-params", k).parents(".control-group").hide()
        } else {
            $("textarea[id$=configuration_rawBody]", k).parents(".control-group").hide();
            $("table.table-body.table-action-http-params", k).parents(".control-group").show()
        }
    });
    $(".table-settings .tr-delete").live("click", function() {
        $(this).parents("tr").remove()
    });
    $('[id$="platetype_templateType"]').change(function() {
        $(".plate-template-type").hide().filter(".plate-template_type_" + $(this).val()).show()
    }).change();
    $("#CRMBundle_OrdersSettings_Type_orders_breakdown_by_managers").change(function() {
        if ($(this).val() == 1) {
            $(this).parent().find(".additional").show()
        } else {
            $(this).parent().find(".additional").hide()
        }
    });
    $("#CRMBundle_OrdersSettings_Type_orders_breakdown_by_managers").change();
    $("select.with_additional ").change(function() {
        var k = $(this).form;
        if ($(this).val() == 1) {
            $(".additional", k).removeClass("hide").show()
        } else {
            $(".additional", k).hide()
        }
    });
    $("select.with_additional").change();
    $("#CRMBundle_MainSettings_Type_statuses_matrix_type").change(function() {
        if ($(this).val() == "order_types_user_groups") {
            $(this).parent().find(".additional").show()
        } else {
            $(this).parent().find(".additional").hide()
        }
    });
    $("#CRMBundle_OrdersSettings_Type_statuses_matrix_type").change();
    $("#intaro_crmuserbundle_grouptype_isManager").change(function() {
        if ($(this).is(":checked")) {
            $("#group_breakdown_settings").show()
        } else {
            $("#group_breakdown_settings").hide()
        }
    });
    $("#intaro_crmuserbundle_grouptype_isManager").change();
    $("#intaro_crmuserbundle_grouptype_makeBreakdownByOrderMethods").change(function() {
        if ($(this).is(":checked")) {
            $("#group_breakdown_order_methods_settings").show()
        } else {
            $("#group_breakdown_order_methods_settings").hide()
        }
    });
    $("#intaro_crmuserbundle_grouptype_makeBreakdownByOrderMethods").change();
    $("#intaro_crmuserbundle_grouptype_orderAccess").change(function() {
        if ($(this).val() == "order_access.by_order_types_and_sites") {
            $("#order-access-settings").show()
        } else {
            $("#order-access-settings").hide()
        }
    });
    $("#intaro_crmuserbundle_grouptype_orderAccess").change();
    $("#intaro_crmuserbundle_grouptype_isDeliveryMen").change(function() {
        if ($(this).is(":checked")) {
            $("#group_delivery_settings").show()
        } else {
            $("#group_delivery_settings").hide()
        }
    });
    $("#intaro_crmuserbundle_grouptype_isDeliveryMen").change();
    $("#intaro_crmapibundle_apikeytype_accessType").change(function() {
        if ($(this).val() == "access_selective") {
            $("#api-access-settings").show()
        } else {
            $("#api-access-settings").hide()
        }
    });
    $("#intaro_crmapibundle_apikeytype_accessType").change();
    $(".color-choose-widget > li").bind("click", function() {
        var l = $(this),
            m = l.parent().data("input"),
            k = l.data("color");
        l.parent().children().removeClass("active");
        l.addClass("active");
        $(m).val(k)
    });
    $("#intaro_crmbundle_storesettingtype_forAllSites").change(function() {
        if ($(this).is(":checked")) {
            $("#store_default_shipment").show();
            $("#store_site_shipment").hide()
        } else {
            $("#store_site_shipment").show();
            $("#store_default_shipment").hide()
        }
    });
    $("#intaro_crmbundle_storesettingtype_forAllSites").change();
    $("#call_tracking_settings_useOrderMethod").change(function() {
        $("#call_tracking_settings_orderMethod").parents(".control-group").toggleClass("hide")
    });
    $(".integration__list a.telephony-popup-info").click(function() {
        var k = $(this).data("popup-name");
        var l = $("#telephony-" + k + "-popup");
        l.intaroPopup()
    })
});

function escapeHtml(b) {
    var a = {
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#39;",
        "/": "&#x2F;"
    };
    return String(b).replace(/[&<>"'\/]/g, function c(d) {
        return a[d]
    })
};
jQuery.fn.extend({
    select: function(a) {
        return this.each(function() {
            new jQuery.eSelect(this, a)
        })
    }
});
jQuery.eSelect = function(g, b) {
    if (g.selectInstalled) {
        return
    }
    g.selectInstalled = true;
    var f = b || {},
        e = jQuery(g),
        d, h;
    f.type = f.type || 1;
    switch (f.type) {
        case 2:
            d = "select";
            break;
        case 1:
            d = "param-select";
            break
    }
    var a = (e.attr("disabled") == "disabled") ? " disabled" : "";
    e.hide();
    e.next(".select-value").remove();
    e.next("." + d).remove();
    $('<div class="' + d + "-value" + a + '">' + e.children(":selected").text() + '<span></span></div><ul class="' + d + a + '"></ul>').insertAfter(e);
    for (var c = 0; c < e.children().length; c++) {
        e.next().next("ul").append('<li rel="' + e.children().eq(c).val() + '">' + e.children().eq(c).text() + "</li>")
    }
    e.next().next("ul").css({
        top: e.next().position().top + 23 + "px",
        left: e.next().position().left + "px"
    });
    if (f.type == 2) {
        e.next().next("ul").css({
            width: e.next().width() + 10 + "px"
        })
    }
    e.next().next("ul").children().eq(e.children("option").index(e.children("option:selected"))).addClass("selected");
    $(".select-value:not(.disabled, .show-list)").live("click", function() {
        var i = $(this).next("ul");
        if (i.not(":visible")) {
            $(".select").hide();
            i.slideDown(200)
        }
        $("html").bind("click", function() {
            $(".select").hide()
        })
    });
    $("." + d + ">li").live("click", function() {
        var j = $(this).parent().prev();
        var i = j.prev("select");
        $(this).parent("ul").children().removeClass("selected");
        $(this).addClass("selected");
        i.val(j.prev("select").children().eq($(this).parent().children("li").index($(this))).val());
        i.change();
        j.html($(this).text() + "<span></span>");
        if (f.type == 2) {
            $(this).parent().hide()
        }
    })
};
var changeSelects = function(a) {
    $("._param-select", a).select({
        type: 1
    });
    $("._select", a).select({
        type: 2
    });
    $(".oc-select select", a).select({
        type: 1
    })
};
jQuery.fn.extend({
    sliderPrice: function(a) {
        return this.each(function() {
            new jQuery.eSliderPrice(this, a)
        })
    }
});
jQuery.eSliderPrice = function(c, d) {
    c.sliderPrice = this;
    var b = d || {},
        t = jQuery(c),
        a = t.find(".sp-roll-left"),
        l = t.find(".sp-roll-right"),
        s = a.parent(),
        k = t.find("input").eq(0),
        i = t.find("input").eq(1),
        f = +t.find(".sp-left-param").text().replace(/[ ]/g, ""),
        h = +t.find(".sp-right-param").text().replace(/[ ]/g, ""),
        n = 170,
        m = f,
        o = h,
        q = +t.data("min-delta") || 0,
        j = !q ? 20 : 0;
    r();
    if ($(k).attr("placeholder") === undefined && k.val() == "") {
        k.val(f)
    }
    if ($(i).attr("placeholder") === undefined && i.val() == "") {
        i.val(h)
    }
    if (parseFloat(k.val()) > parseFloat(i.val())) {
        i.val(k.val())
    }
    if (n != s.parent().width() - j && s.parent().width() > 40) {
        n = s.parent().width() - j;
        $(window).resize(function() {
            setTimeout(function() {
                n = s.parent().width() - j;
                p()
            }, 350)
        })
    }
    p();
    t.find("input[type=text]").bind({
        keydown: function(u) {
            if (u.keyCode != 40 && u.keyCode != 39 && u.keyCode != 38 && u.keyCode != 37 && u.keyCode != 13 && !u.ctrlKey && !u.metaKey) {
                if (u.keyCode != 8 && u.keyCode != 46) {
                    if ((u.keyCode < 48 || u.keyCode > 57) && (u.keyCode < 96 || u.keyCode > 105)) {
                        u.preventDefault()
                    }
                }
            }
        },
        input: function() {
            if (!/[0-9]/.test(+$(this).val())) {
                $(this).val(0)
            }
        },
        blur: function() {
            if ($(this).val() == "") {
                $(this).val(0)
            }
        },
        focus: function() {
            if ($(this).val() == "0") {
                $(this).val("")
            }
        },
        change: function() {
            p()
        }
    });
    l.bind({
        mousedown: function(w) {
            var v = $(this),
                u = w.pageX,
                x = parseFloat(s.css("right"));
            v.addClass("m-down");
            $("html").bind({
                mousemove: function(z) {
                    var y = x + u - z.pageX;
                    y = y < 0 ? 0 : y;
                    o = h - Math.round(y / (n / (h - f)));
                    if (o - q < m) {
                        o = m + q;
                        y = Math.round((h - o) / (h - f) * n)
                    }
                    s.css("right", y);
                    if (t.hasClass("with-drop")) {
                        g("right", o, y)
                    }
                    i.val(o)
                },
                dragstart: function() {
                    return false
                },
                mouseup: function() {
                    $(this).unbind("mousemove dragstart");
                    v.removeClass("m-down")
                }
            });
            return false
        },
        mouseup: function() {
            $(this).removeClass("m-down")
        }
    });
    a.bind({
        mousedown: function(x) {
            var w = $(this),
                u = x.pageX,
                v = parseFloat(s.css("left"));
            w.addClass("m-down");
            $("html").bind({
                mousemove: function(A) {
                    var z = v - u + A.pageX;
                    z = z < 0 ? 0 : z;
                    m = f + Math.round(z / (n / (h - f)));
                    var y = parseInt(k.attr("min"));
                    if (!isNaN(y) && m < y) {
                        return
                    }
                    if (m + q > o) {
                        m = o - q;
                        z = Math.round((m - f) / (h - f) * n)
                    }
                    s.css("left", z);
                    if (t.hasClass("with-drop")) {
                        g("left", m, z)
                    }
                    k.val(m)
                },
                dragstart: function() {
                    return false
                },
                mouseup: function() {
                    $(this).unbind("mousemove dragstart");
                    w.removeClass("m-down")
                }
            });
            return false
        },
        mouseup: function() {
            $(this).removeClass("m-down")
        }
    });
    t.bind("update", function() {
        r();
        p()
    });
    t.bind("change", function() {
        r();
        p()
    });

    function r() {
        var u = false;
        if (i.val() == h) {
            u = true
        }
        f = +t.find(".sp-left-param").text().replace(/[ ]/g, "");
        h = +t.find(".sp-right-param").text().replace(/[ ]/g, "");
        if (u) {
            i.val(h)
        }
    }

    function p() {
        var v = parseInt(k.val()),
            z = parseInt(i.val()),
            y = k.data("force") || i.data("force");
        v = v === "" ? f : v;
        z = z === "" ? h : z;
        z = z - q < v ? v + q : z;
        var x = v < f && !y ? f : v > h && !y ? h : v;
        var w = z < f && !y ? f : z > h && !y ? h : z;
        m = v < f ? f : v > h ? h : v;
        o = z < f ? f : z > h ? h : z;
        o = m > o ? m : o;
        if ($(k).attr("placeholder") === undefined) {
            k.val(x)
        }
        if ($(i).attr("placeholder") === undefined) {
            i.val(w)
        }
        var A = Math.round((m - f) / (h - f) * n),
            u = Math.round((h - o) / (h - f) * n);
        if (m + q > o) {
            m = o - q
        }
        s.css({
            left: A,
            right: u
        });
        if (t.hasClass("with-drop")) {
            g("right", w, u);
            g("left", x, A)
        }
    }

    function g(C, u, x) {
        var E = {
                left: t.find(".sp-drop-left"),
                right: t.find(".sp-drop-right")
            },
            z = C == "left" ? "right" : "left",
            F = E[C],
            y = F.parent(),
            B = x,
            A = 6,
            w = F.outerWidth() / 2,
            D;
        t.find(".sp-count-" + C).text(e(u));
        E.right.css("margin-right", 0);
        E.left.css("margin-left", 0);
        if (C == "left") {
            D = E.right.offset().left - A - w - y.offset().left
        } else {
            D = y.width() - (parseFloat(E.left.css("left")) + E.left.outerWidth() / 2 + A / 2 + w)
        }
        if (B > D) {
            var v = 0;
            if (parseFloat(E[z].css(z)) !== E[z].outerWidth() / 2) {
                v = (B - D) / 2
            }
            E[z].css("margin-" + z, -v);
            F.css(C, D + v)
        } else {
            if (B < w) {
                F.css(C, w)
            } else {
                F.css(C, B)
            }
        }
    }

    function e(u) {
        return t.data("format") && u > 0 ? "+" + u : u
    }
};
var IntaroPopup = (function() {
    var l = 300;
    var h = "intaroPopupClosed";
    var b = "stat-box-popup-bg";
    var e = "overpage";
    var s = "o-bg black-red-loader";
    var f = "stat-box-popup-container";
    var q = "stat-box-wrapper";
    var r = "stat-box-wrapper-abs";
    var p = $("<div>").addClass(b).addClass(e);
    var j = $("<div>").addClass(f);
    var d = $("<div>").addClass(q).addClass(r).html(j);
    var c = $("<div>").addClass(e).addClass("o-w").addClass(s);

    function m(v) {
        var t = this;
        v = v || {};
        if (!v.popup) {
            $.error("missing input")
        }
        var u = $(".stat-box-popup:visible");
        this.popup = v.popup;
        this.popup.bind(h, $.proxy(this.close, t));
        this.initContentEl = this.popup.find(".stat-content");
        this.isDynamic = v.options.url || false;
        if (!this.domBuilded) {
            this._buildDom()
        }
        this.isClosable = !this.popup.hasClass(".not-close");
        this.popupWrapper = this.popup.closest(g(q));
        if (v.options.wrapperClass) {
            this.popupWrapper.addClass(v.options.wrapperClass)
        }
        if (!this.eventsBinded) {
            this._bindEvents()
        }
        if (u.length > 0 && !u.is(this.popup)) {
            this._replace(v, u)
        } else {
            this._open(v)
        }
    }
    $.extend(m.prototype, {
        _open: function(t) {
            this._showBackground();
            if (this.initContentEl.length > 0 || !this.isDynamic) {
                this.open()
            }
            if (this.isDynamic) {
                this._showLoader();
                this._loadData(t.options)
            }
        },
        _replace: function(v, u) {
            if (u.find(g(e)).length === 0) {
                u.append($(c))
            }
            var t = this;
            if (this.isDynamic) {
                this._loadData(v.options, function() {
                    t._showBackground();
                    u.find($(c)).remove();
                    u.intaroPopup("close")
                })
            } else {
                this._open(v);
                u.find($(c)).remove();
                u.intaroPopup("close")
            }
        },
        _buildDom: function() {
            this.popup.wrap(d);
            this.popup.addClass("stat-box-popup-content");
            $("html").addClass("noscroll");
            this.domBuilded = true
        },
        _bindEvents: function() {
            var t = this;
            this.popup.find(".close").live("click", n);
            this.popup.find(".close-button").live("click", n);
            $(window).bind("keyup.popupCloseByKey", function(u) {
                if (u.keyCode == 27 && t.popup.length) {
                    $(".close", t.popup).click()
                }
            });
            if (this.isClosable) {
                $(".stat-box-wrapper-abs").live("click", function() {
                    $(".close", t.popup).click()
                });
                $(".stat-box-wrapper-abs .stat-box-popup-content").live("click", function(u) {
                    u.stopPropagation()
                })
            }
            this.eventsBinded = true
        },
        _showBackground: function() {
            if (this.popupWrapper.prev("." + e).length === 0) {
                this.popupWrapper.before(p);
                this.popupWrapper.prev().fadeIn()
            }
        },
        _showLoader: function() {
            if (this.initContentEl.length > 0) {
                this.initContentEl.css("min-height", l + "px").addClass(e).addClass(s)
            } else {
                this.popupWrapper.prev().addClass(s)
            }
        },
        _loadData: function(u, w) {
            var v = this;
            var t = u.dataType || "html";
            this.xhr = $.ajax({
                url: u.url,
                data: u.data || {},
                type: u.type || "GET",
                dataType: t,
                success: function(x) {
                    if (t == "html") {
                        v.setContent(x)
                    }
                    v.popup.find(".stat-content").removeClass(e).removeClass(s);
                    v.open();
                    if (typeof w == "function") {
                        w(null)
                    }
                    if (typeof u.onSuccess == "function") {
                        u.onSuccess(x)
                    }
                },
                error: function(y, z, x) {
                    v._hideLoader();
                    if (z !== "abort" && typeof u.onError == "function") {
                        u.onError(y, z, x)
                    }
                },
                complete: function() {
                    v.xhr = null
                }
            })
        },
        _hideLoader: function() {
            if (this.initContentEl.length > 0) {
                this.initContentEl.removeClass(e).removeClass(s)
            } else {
                this.popupWrapper.prev().removeClass(s)
            }
        },
        _unBindEvents: function() {
            $(".stat-box-wrapper-abs").die();
            this.popup.unbind(h);
            $(window).unbind("keyup.popupCloseByKey");
            this.popup.find(".close").die("click");
            this.popup.find(".close_button").die("click")
        },
        _destroy: function() {
            this.popup.hide();
            this.popup.unwrap();
            this.popup.unwrap();
            this.popup.prev(g(b)).remove();
            this.popup.removeData("intaroPopup");
            if ($(".stat-box-popup:visible").length === 0) {
                $("html").removeClass("noscroll")
            }
            this._unBindEvents()
        },
        open: function k() {
            this.popup.trigger(jQuery.Event("beforeOpen"));
            this.popupWrapper.show();
            this.popup.show();
            this.popup.trigger(jQuery.Event("afterOpen"))
        },
        updateContent: function i(t) {
            this.setContent("");
            this._showLoader();
            this._loadData(t)
        },
        replace: function o(t) {
            if (this.popup.find("." + e).length === 0) {
                this.popup.append($(c))
            }
            if (this.isDynamic) {
                this._loadData(t)
            } else {
                this._open(t);
                this.popup.find("." + e).remove()
            }
        },
        abortXHR: function() {
            if (this.xhr) {
                this.xhr.abort()
            }
        },
        close: function() {
            this.abortXHR();
            if (this.isDynamic && window.tinymce) {
                this.popup.find(".tinymce").each(function() {
                    var t = $(this).attr("id");
                    if (t) {
                        tinymce.remove("#" + t)
                    }
                })
            }
            if (this.isDynamic) {
                this.setContent("")
            }
            this._destroy()
        },
        setContent: function a(t) {
            if (this.initContentEl.length > 0) {
                this.initContentEl.css("min-height", "").html(t).initJsControls()
            } else {
                this._hideLoader();
                this.popup.html(t).initJsControls()
            }
        }
    });

    function n(u) {
        u.preventDefault();
        var t = jQuery.Event("beforeClose");
        $(this).trigger(t);
        if (!t.isPropagationStopped()) {
            $(this).parents(".stat-box-popup,.stat-box-wrapper").hide().trigger(h);
            $(".stat-box-popup .dropdown ul").hide()
        }
    }

    function g(t) {
        return "." + t
    }
    return m
})();
(function(h) {
    var d;
    var c = "intaroPopup";
    d = {
        initialize: function b(j) {
            j = j || {};
            return this.each(i);

            function i() {
                var l = h(this),
                    k;
                if (l.data("intaroPopup")) {
                    return (l.intaroPopup("replace", j))
                }
                k = new IntaroPopup({
                    popup: l,
                    options: j
                });
                l.data(c, k)
            }
        },
        close: function() {
            return this.each(i);

            function i() {
                var k = h(this),
                    j;
                if (j = k.data(c)) {
                    j.close()
                }
            }
        },
        open: function e() {
            return this.each(i);

            function i() {
                var k = h(this),
                    j;
                if (j = k.data(c)) {
                    j.open()
                }
            }
        },
        updateContent: function g(j) {
            return this.each(i);

            function i() {
                var l = h(this),
                    k;
                if (k = l.data(c)) {
                    k.updateContent(j)
                }
            }
        },
        setContent: function g(i) {
            return this.each(j);

            function j() {
                var l = h(this),
                    k;
                if (k = l.data(c)) {
                    k.setContent(i)
                }
            }
        },
        replace: function f(i) {
            return this.each(j);

            function j() {
                var l = h(this),
                    k;
                if (k = l.data(c)) {
                    k.replace(i)
                }
            }
        },
        abortXHR: function a(j) {
            return this.each(i);

            function i() {
                var l = h(this),
                    k;
                if (k = l.data(c)) {
                    k.abortXHR(j)
                }
            }
        }
    };
    h.fn.intaroPopup = function(j) {
        var i;
        if (d[j] && j !== "initialize") {
            i = this.filter(function() {
                return !!h(this).data(c)
            });
            return d[j].apply(i, [].slice.call(arguments, 1))
        } else {
            return d.initialize.apply(this, arguments)
        }
    }
})(jQuery);

function PopupProduct(b, a) {
    this.$popup = b;
    this.$form = a;
    this.preOpen = null;
    this.lastFilterData = null;
    this.init = function() {
        var c = this;
        c.$popup.find(".submit-group input.btn").live("click", function() {
            c.filter($(this))
        });
        c.$popup.find(".search-input .btn").live("click", function() {
            c.$popup.find(".submit-group input.btn").click()
        });
        c.$popup.find(":input").live("keypress", function(d) {
            if (d.keyCode == 13) {
                d.preventDefault();
                $(this).parents(".popup-with-item-list").find(".submit-group .btn").first().click()
            }
        });
        c.$popup.find(".submit-group .form-reset-btn").live("click", function(d) {
            c.filterReset($(this))
        });
        $(".trigger-filter-update").live("change", function() {
            c.filterUpdate($(this))
        });
        c.$popup.find(".more").live("click", function() {
            c.loadNextPage($(this))
        });
        c.$popup.find(".input-group .product-flag").live("click", function() {
            $(this).prev("input").click()
        });
        c.$popup.find(".table-with-data .has-children").live("click", function(d) {
            if ($(d.target).is(".tr-view,.tr-view-crm,.tr-view-site") || $(this).parents(".popup-with-item-list").hasClass("segment-popup")) {
                return
            }
            c.toggleChilds($(this), d)
        });
        c.$popup.find("[id $= _priceType]").live("chosen:showing_dropdown", function(f, d) {
            c.filterGetPriceTypes($(this), d)
        });
        c.$popup.find("[id $= _priceType]").live("change", function() {
            var e = $(this).parent().siblings(".slider-price");
            var f = e.data("max-prices");
            var d = 0;
            if (f[$(this).val()]) {
                d = f[$(this).val()]
            }
            e.find(".sp-right-param").text(parseInt(d));
            if (e[0].sliderPrice) {
                e[0].sliderPrice.updateLimits()
            }
        });
        c.$popup.find("[id $= _priceType]").live("chosen:ready", function() {
            $(this).change()
        });
        c.$popup.find("td.price-ins ul.price-type__list > li > a").live("dropdown:click", function(f) {
            var d = $(f.target).parents("td.price-ins").parent();
            d.attr("data-price", $(f.target).attr("data-value"));
            d.attr("data-price-type", $(f.target).attr("data-price-type"));
            d.attr("data-price-type-name", $(f.target).attr("data-price-type-name"));
            d.find("td.price-ins__cell_price_type").text($(f.target).attr("data-price-type-name")).attr("title", $(f.target).attr("data-price-type-name"))
        })
    };
    this.open = function() {
        if (this.preOpen instanceof Function) {
            this.preOpen()
        }
        this.$popup.intaroPopup({
            wrapperClass: "fixed-popup-wrapper"
        });
        if (b.find(".rt-col .no-filter-param-text").length) {
            b.find(".submit-group input.btn").click()
        }
        this.$popup.initJsControls()
    };
    this.filter = function(f) {
        var c = this;
        if (f.hasClass("disabled")) {
            return
        }
        f.addClass("disabled");
        $(".no-filter-param-text", this.$popup).remove();
        $(".table-with-head", this.$popup).fadeIn(300);
        $(".table-with-data", this.$popup).after('<div class="loader"></div>').find("tr:gt(0)").remove();
        var h = this.getFilterDataObject();
        var e = this.$popup.find('[id $= "_minPrice"]').val();
        if (e == 0) {
            var g = this.$popup.find('[id $= "_maxPrice"]').parents(".input-group:first").find(".sp-right-param").text();
            for (key in h) {
                if (h[key].name != undefined && h[key].name.substring(h[key].name.length - 10) == "[maxPrice]" && g == h[key].value) {
                    h.splice(key, 1);
                    break
                }
            }
        }
        var d = this.$popup.data("content-url");
        $.ajax({
            url: d,
            data: h,
            type: "POST",
            success: function(j) {
                c.lastFilterData = h;
                $(".table-with-data tr:gt(0)", c.$popup).remove();
                $(".table-with-data tr:first", c.$popup).after(j).parents(".table-with-data").hide();
                var i = 0;
                $(".table-with-data", c.$popup).data("page", 1).fadeIn(600);
                if (c.preOpen instanceof Function) {
                    c.preOpen()
                }
                $(".message-indic", c.$popup).click(commentIconClick)
            },
            error: function() {
                alert(_translate("alert.data_load_error"))
            },
            complete: function() {
                f.removeClass("disabled");
                $(".rt-col .loader", c.$popup).remove()
            }
        })
    };
    this.filterReset = function(d) {
        var c = d.parents(".ft-lt");
        c.find('input[type="text"]').val("");
        c.find('input[type="checkbox"]').attr("checked", false);
        c.find(".slider-price").sliderPrice();
        c.find("select:not(.trigger-filter-update)").val("");
        c.find("select:not(.trigger-filter-update)").trigger("chosen:updated")
    };
    this.filterUpdate = function(h) {
        var g = h.parents(".lt-col:first");
        var e = g.find(".lt-popup-filter"),
            d = {},
            c = e.data("url"),
            f = e.data("xhr");
        $(".trigger-filter-update").each(function() {
            d[$(this).attr("name")] = $(this).val()
        });
        e.find(">div:not(.main-filter-field)").remove();
        if (f) {
            f.abort()
        }
        e.append('<div class="loader bar"></div>');
        f = $.ajax({
            url: c,
            data: d,
            type: "POST",
            success: function(i) {
                e.html(i);
                e.initJsControls();
                $(".slider-price", e).sliderPrice();
                e.find('[id $= "_priceType"]').parent().hide();
                e.find('[id $= "_priceType"]').trigger("chosen:showing_dropdown", {
                    selectFirst: true
                })
            },
            error: function(i, j) {
                if (j != "abort") {
                    alert(_translate("alert.product_group_load_error"))
                }
            },
            complete: function() {
                $(".lt-col .loader", e).fadeOut(250).remove();
                e.data("xhr", null)
            }
        });
        e.data("xhr", f)
    };
    this.loadNextPage = function(f) {
        var c = this;
        var e = this.$popup.find(".table-with-data");
        var d = this.$popup.data("content-url");
        var h = parseInt(e.data("page")) + 1;
        var g = this.lastFilterData;
        if (!g) {
            g = []
        }
        g.push({
            name: "page",
            value: h
        });
        f.html('<div class="small-loader"></div>');
        $.ajax({
            url: d,
            data: g,
            type: "POST",
            success: function(j) {
                var i = $(j);
                $("> tbody > tr:last", e).remove();
                $("> tbody > tr:last", e).after(i).nextAll().hide().filter("tr:not(.is-child)").fadeIn(600);
                if (c.preOpen instanceof Function) {
                    c.preOpen()
                }
                $(".message-indic", i).click(commentIconClick);
                e.data("page", h);
                e.trigger("data-load-success")
            },
            error: function() {
                alert(_translate("alert.data_load_error"))
            }
        })
    };
    this.toggleChilds = function(d) {
        var f = d;
        var g = f.data("product-id");
        var e = f.children("td").length;
        f.toggleClass("show");
        if (f.hasClass("show")) {
            var c = 0;
            $elems = f.nextAll("[data-product-id=" + g + "]");
            $elems.show().each(function() {
                c += parseInt(d.css("height"))
            }).hide();
            f.after('<tr id="empty-cel"><td colspan="' + e + '"></td></tr>').next().children().css({
                display: "none",
                height: c + "px",
                padding: 0,
                backgroundColor: "#f7f7f7"
            }).filter(":not(:animated)").slideDown(250, function() {
                $("#empty-cel").remove();
                $elems.show().children().children().css("opacity", 0).animate({
                    opacity: 1
                }, 250)
            })
        } else {
            var c = 0;
            $elems = f.nextAll("[data-product-id=" + g + "]");
            $elems.each(function() {
                c += parseInt(d.css("height"))
            });
            $elems.children().fadeOut(150, function() {
                if (!f.next().is(".animate-row")) {
                    f.after('<tr id="empty-cel" class="animate-row"><td colspan="' + e + '"></td></tr>').next().children().css({
                        height: c + "px",
                        padding: 0,
                        backgroundColor: "#f7f7f7"
                    }).filter(":not(:animated)").slideUp(250, function() {
                        $("#empty-cel").remove();
                        $elems.hide().children().show()
                    })
                }
            })
        }
    };
    this.filterGetPriceTypes = function(e, g) {
        if (g && typeof g.selectFirst == "undefined") {
            selectFirst = false
        } else {
            selectFirst = g.selectFirst
        }
        var c = '<li class="disabled-result loader" style="margin: auto; height: 30px; width: 30px; padding:0px"></li>';
        var h = e.siblings(".chosen").find(".chosen-results");
        h.find("li").removeClass("active-result");
        h.find("li").addClass("disabled-result");
        h.find("li").hide();
        h.append(c);
        var f = function(i) {
            var j = false;
            e.find("option").removeAttr("disabled");
            e.find("option").each(function() {
                if ($(this).attr("value") != "" && i.indexOf(parseInt($(this).attr("value"))) == -1) {
                    $(this).attr("disabled", true);
                    $(this).hide()
                } else {
                    $(this).show();
                    if (selectFirst && !j) {
                        e.find("option").removeAttr("selected");
                        $(this).attr("selected", "selected");
                        j = true
                    }
                }
            });
            e.trigger("chosen:updated")
        };
        var d = function() {
            h.find(".loader").remove();
            if (selectFirst) {
                e.parent().show()
            }
        };
        if (typeof Order != "undefined") {
            Order.getAvailablePriceTypes({
                success: f,
                complete: d
            })
        } else {
            if (e.data("ajax")) {
                e.data("ajax").abort()
            }
            ajaxRequest = $.ajax({
                url: Routing.generate("crm_price_types_list_for_order"),
                data: [],
                type: "POST",
                success: function(i) {
                    if (!i.success) {
                        return
                    }
                    f(i.priceTypes)
                },
                error: function(j, i) {
                    if (i != "abort") {
                        alert(_translate("alert.request_error_try_later"))
                    }
                    return false
                },
                complete: function() {
                    d()
                }
            });
            e.data("ajax", ajaxRequest)
        }
    };
    this.getFilterDataObject = function() {
        if (this.$form && this.$form.length) {
            var c = this.$form.serializeArray();
            if ("undefined" !== typeof ORDER_OPTIONS && "orderId" in ORDER_OPTIONS) {
                c.push({
                    name: "orderId",
                    value: ORDER_OPTIONS.orderId
                })
            }
        } else {
            var c = [];
            $(":input", this.$popup).each(function() {
                if ($(this).is(":checkbox")) {
                    if ($(this).is(":checked")) {
                        c.push({
                            name: $(this).attr("name"),
                            value: true
                        })
                    }
                } else {
                    if (value = $(this).val()) {
                        c.push({
                            name: $(this).attr("name"),
                            value: value
                        })
                    }
                }
            })
        }
        return c
    }
};
CRM_ERROR_MESSAGE = _translate("message.error_on_request_handle");
$(function() {
    var e = "items-type",
        o = "items-variant",
        n = "my",
        v = "all",
        C = "leftbar-cls-",
        p = "-leftbar-list",
        j = 2,
        D = "#filter_",
        c = "#leftbar-classifier",
        s = "leftbar-lists",
        f = "manager-id-",
        r = ["manager", "source"],
        t = $.browser.msie && $.browser.version < 9;
    var y = null;
    var z = "";
    $("form.safe-trans").each(function() {
        var G = $(this).serializeArray();
        var E = [];
        for (var F = 0; F < G.length; F++) {
            if (G[F]["name"].length && G[F]["value"].trim().length) {
                E.push(G[F])
            }
        }
        if (z.length) {
            z += "&"
        }
        z += $.param(E)
    });
    secToTimeString = function(G) {
        var F = new Number();
        var E = new Number();
        F = Math.floor(G);
        E = Math.floor(F / 60);
        E = E >= 10 ? E : "0" + E;
        F = Math.floor(F % 60);
        F = F >= 10 ? F : "0" + F;
        return E + ":" + F
    };
    safeTransFormsHasChanges = function() {
        var E = "";
        $("form.safe-trans").each(function() {
            var H = $(this).serializeArray();
            var F = [];
            for (var G = 0; G < H.length; G++) {
                if (H[G]["name"].length && H[G]["value"].trim().length) {
                    F.push(H[G])
                }
            }
            if (E.length) {
                E += "&"
            }
            E += $.param(F)
        });
        return E != z
    };
    if (z.length) {
        y = window.addEventListener || window.attachEvent ? window : document.addEventListener ? document : null;
        if (y) {
            $(y).bind("beforeunload", function() {
                if (!safeTransFormsHasChanges()) {
                    return
                }
                return _translate("message.unsaved_changes")
            })
        }
    }
    $(document.body).delegate(":input", "keydown", function(F) {
        var E = $(this);
        if (F.keyCode == 13) {
            if (E.is("textarea")) {
                var G = false;
                if ("undefined" !== typeof F.metaKey) {
                    G = F.metaKey
                }
                if (F.ctrlKey || G) {
                    E.trigger("submitOnInputField")
                }
            } else {
                E.trigger("submitOnInputField")
            }
        }
    });
    $("form.safe-trans").live("submit", function() {
        y.onbeforeunload = null
    }).delegate(":input", "submitOnInputField", function(H) {
        var F = $(this);
        if (F.parents(".stat-box-popup").length) {
            return true
        }
        var E = F.parents("form.safe-trans");
        var G = E.find(".save-box .btn-save");
        if (G.length > 0) {
            G.click()
        }
    });
    $(document.body).delegate(".stat-box-popup :input", "submitOnInputField", function(H) {
        var F = $(this);
        var I = F.parents(".stat-box-popup");
        var E = I.find("form");
        var G = null;
        if (E.length) {
            E.submit()
        } else {
            G = I.find(":submit:first");
            if (G.length) {
                G.click()
            } else {
                G = I.find(":button:first");
                if (G.length) {
                    G.click()
                }
            }
        }
        return false
    });
    var l = function(F) {
        for (var E in F) {
            if ($("input" + D + E).length > 0) {
                $(D + E).eq(0).val(F[E])
            }
            if ($("select" + D + E).length > 0) {
                $("select" + D + E).next().next("ul").children().each(function() {
                    if ($(this).attr("rel") == F[E]) {
                        $(this).click()
                    }
                })
            }
        }
        $(".sp-text-input").each(function() {
            var G = $(".sp-roll-left", $(this).prev()).parent(),
                L = 170,
                J = parseFloat($(".sp-left-param", $(this).prev()).text()),
                N = parseFloat($(".sp-right-param", $(this).prev()).text()),
                M = N - J,
                I = $("input", this),
                H = parseFloat($(I[0]).val()),
                K = parseFloat($(I[1]).val());
            if (H <= J) {
                $(I[0]).val(J)
            } else {
                G.css("left", Math.round(L * (H - J) / M) + "px")
            }
            if (K >= N) {
                $(I[1]).val(N)
            } else {
                G.css("right", Math.round(L * (N - K) / M) + "px")
            }
        })
    };
    var A = function(F) {
        for (var E in F.filter) {
            if (F.filter[E] == "") {
                delete F.filter[E]
            }
        }
        return F
    };
    $("input[type=text],input[type=password],textarea").live({
        focus: function() {
            if ($(this).val() == $(this).attr("title")) {
                $(this).val("")
            }
        },
        blur: function() {
            if ($(this).val() == "") {
                $(this).val($(this).attr("title"))
            }
        }
    });
    $("input.clear-btn[type=text]").live("keyup", function() {
        var E = $(this);
        if (!E.val()) {
            E.addClass("empty")
        } else {
            E.removeClass("empty")
        }
    });
    $("input.clear-btn[type=text]").trigger("keyup");
    $(".clear-input").live("click", function() {
        var E = $("#" + $(this).data("for"));
        E.val("");
        E.trigger("keyup").focus()
    });
    $(".btn-red>div, .btn-big-red>div, .no-btn-big>div,.select-page>a,.param-select>ul>li,.btn-big>div,.btn>div,.btn-red-o,.btn-red-ok,.ui-datepicker-prev,.ui-datepicker-next,.ui-datepicker-calendar td:not(.ui-datepicker-unselectable),.sc-btn-l,.sc-btn-r").live({
        mousedown: function() {
            $(this).addClass("m-down")
        },
        "mouseup mouseout": function() {
            $(this).removeClass("m-down")
        }
    });
    $(".save-box .del-btn, .buttons-bar .del-btn").live("click", function(F) {
        if ($(this).hasClass("disabled")) {
            F.preventDefault();
            return
        }
        if (confirm($(this).data("alert"))) {
            if ("BUTTON" == $(this)[0].nodeName) {
                return true
            } else {
                var E = $('<form method="POST" action="' + this.href + '" />');
                $("body").append(E);
                if ($(this).data("return-href").length) {
                    E.append('<input type="hidden" name="return" value="' + $(this).data("return-href") + '" />')
                }
                E[0].submit()
            }
        }
        return false
    });
    $(".modern-table .del-btn").live("click", function() {
        $(this).parents("tr").remove();
        return false
    });
    $(".trigger-btable.tb-hide").live("click", function() {
        var E = $(this).closest(".btc-left");
        E.animate({
            "margin-left": -16 + "%"
        }, function() {
            E.hide();
            E.parent().prev(".tb-show").fadeIn()
        });
        E.next(".btc-right").animate({
            width: 100 + "%"
        })
    });
    $(".trigger-btable.tb-show").live("click", function() {
        var E = $(this).next().children(".btc-left");
        $(this).hide();
        E.next(".btc-right").animate({
            width: 84 + "%"
        });
        E.show().animate({
            "margin-left": 0 + "%"
        })
    });
    $(".sb-right").live("click", function() {
        $(this).next().fadeIn()
    });
    $("#status-cancel").live("click", function() {
        $(".status-box-popup").hide()
    });
    $("#bf-reset").live("click", function() {
        $(this).closest(".blue-filter").find("form").trigger("reset");
        $(".sp-text-input").each(function() {
            var E = $(".sp-roll-left", $(this).prev()).parent();
            valueLeft = $(".sp-left-param", $(this).prev()).text(), valueRight = $(".sp-right-param", $(this).prev()).text(), inps = $("input", this);
            $(inps[0]).val(parseFloat(valueLeft));
            $(inps[1]).val(parseFloat(valueRight));
            E.css("left", "0");
            E.css("right", "0")
        });
        $(this).closest(".blue-filter").find("form").find(".select:not(.disabled)").each(function() {
            $(this).children("li").eq(0).trigger("click")
        });
        $.bbq.pushState({}, j)
    });
    $("#bf-trigger-show").live("click", function() {
        var E = parseFloat($(this).attr("data-height"));
        if (E < 1 || isNaN(E)) {
            E = 90
        }
        $(this).closest(".blue-filter").find(".bf-alt-parame").css("height", E).slideDown();
        $(this).hide();
        $("#bf-trigger-hide").show()
    });
    $("#bf-trigger-hide").live("click", function() {
        $(this).closest(".blue-filter").find(".bf-alt-parame").slideUp();
        $(this).hide();
        $("#bf-trigger-show").show()
    });
    $(".th-menu>div").live("click", function() {
        $(this).children("ul").toggle()
    });
    $(".lb-bg,.lbap-btn>span").live("click", function() {
        if (!t) {
            $(".lb-bg,.lb-box").fadeOut()
        } else {
            $(".lb-bg,.lb-box").hide()
        }
    });
    $(".osmb-2 .osmb-b").live("click", function() {
        $("#osmb-status-popup").css({
            top: $(this).offset().top - 1 + "px",
            left: $(this).offset().left + 1 + "px"
        }).fadeIn()
    });
    $("#osmb-status-popup").live("click", function() {
        $(this).hide();
        return false
    });
    $(".th-sort>div").live("click", function() {
        var E = $(this).parent().attr("data-new-sort-url");
        if (E) {
            $.bbq.pushState($.param.querystring(E), j)
        }
        return !1
    });
    $("#leftbar-lists a, #paginator-top a, #paginator-bottom a").live("click", function() {
        var E = $(this).attr("id");
        var F = $.param.querystring($(this).attr("href"));
        $.bbq.pushState(F, j);
        return !1
    });
    $(".param-select>li").live("click", function() {
        var E = $(this).parent().prev().prev(),
            G = E.attr("id") ? E.attr("id") : "",
            F = $(this).attr("rel") ? $(this).attr("rel") : "",
            H = $.deparam.fragment();
        if (G.indexOf(o) >= 0) {
            H.items_per_page = F
        } else {
            if (G.indexOf(e) >= 0) {
                H.items_type = F;
                if (H.filter && H.filter.manager != userId) {
                    delete H.filter.manager
                }
            } else {
                if (F.indexOf(C) >= 0) {
                    var I = F.substr(C.length) + p;
                    $("#leftbar-lists>li").each(function() {
                        if (I == $(this).attr("id")) {
                            $(this).removeClass("hide")
                        } else {
                            $(this).addClass("hide")
                        }
                    });
                    return !1
                } else {
                    return !1
                }
            }
        }
        H.page = 1;
        $.bbq.pushState(H, j);
        return !1
    });
    $("#filter-form").submit(function() {
        $.bbq.pushState(A($.deparam($(this).serialize())), j);
        return !1
    });
    $("#filter-form-submit").click(function() {
        $("#filter-form").submit()
    });
    $("#bottom-delete-button, #top-delete-button").live("click", function() {
        var E = [];
        $('input[name^="itemId"]:checked').each(function() {
            E.push($(this).val())
        });
        if (E.length && confirm("Are you serious?")) {
            $.ajax({
                url: document.location.pathname,
                type: "POST",
                data: {
                    deleteIds: E.join(",")
                },
                dataType: "json",
                error: function(G, H, F) {},
                success: function(F) {
                    url = $.bbq.getState();
                    url.del = "Y";
                    $.bbq.pushState(url)
                }
            })
        }
        return !1
    });
    changeSelects();
    $(".form-submit").live("click", function() {
        $(this).parents("form").submit()
    });
    $("input.product-select").live("click", function() {
        var F = $(this).parents("tr").next();
        var E = $(this).is(":checked");
        while (F.is(".offer")) {
            if (E) {
                F.show()
            } else {
                F.hide()
            }
            F = F.next()
        }
    });
    $("#rm-left .cb-table .ajax-edit").live("click", function() {
        var E = $(this).data("url");
        if (!E) {
            E = location.pathname + "/edit"
        }
        $(this).parents(".card-box").load(E)
    });
    $("#rm-left .cb-table .ajax-save").live("click", function() {
        var H = $(this).parents(".card-box");
        var E = $(this).data("url");
        if (!E) {
            E = location.pathname + "/info"
        }
        var F = $(this).parents("form");
        var G = $(F).serialize();
        $.ajax({
            url: $(F).attr("action"),
            type: "POST",
            data: G,
            complete: function() {
                $(H).load(E + " .card-box > *")
            }
        })
    });
    $(".script-more").live("click", function() {
        var F = $(this).parents(".cb-table").find(".customer-phone-field").length;
        var E = $(this).data("prototype");
        var G = E.replace(/__name__/g, F);
        var H = $("<tr><td></td><td>" + G + "</td></tr>");
        $(this).parents("tr").before(H)
    });
    $(document).ajaxComplete(function(F, G, E) {
        $("._select").select({
            type: 2
        });
        if (/<form([^>]*)?class=\"([^\"]*)?login([^\"]*)?\"/.test(G.responseText)) {
            location = crmGlobalOptions.loginUrl
        }
    });
    $(".order-status > div").live("click", function() {
        var F = $(this),
            E = F.data("flag");
        if (!E || F.hasClass("no-edit")) {
            return
        }
        var G = F.hasClass("os-select");
        F.toggleClass("os-select", !G);
        $.ajax({
            url: F.data("url"),
            type: "POST",
            data: E + "=" + (G ? "false" : "true"),
            dataType: "json",
            success: function(H) {
                if (H && H.errors && H.errors.length > 0) {
                    F.toggleClass("os-select", G);
                    $.each(H.errors, function() {
                        window.showErrorNotification("" + this)
                    })
                }
            },
            error: function() {
                F.toggleClass("os-select", G)
            }
        })
    });
    $(".prod-status > div").live("click", function() {
        $(this).toggleClass("os-select")
    });
    $(".stat-box .zoom").live("click", function() {
        var E = $(".omni-widget-popup", $(this).parents(".stat-box"));
        if (!E.length) {
            E = $(this).parent().parent().next()
        }
        if (E.length && E.hasClass("stat-box-popup")) {
            E.intaroPopup();
            var F = $(this).parent().find(".dropdown > a").attr("data-value");
            if (E.hasClass("omni-widget-popup") && E.attr("data-need-loading") == "true") {
                E.intaroPopup("updateContent", {
                    url: E.attr("data-update-url"),
                    data: {
                        widgetName: E.attr("data-widget-name")
                    },
                    onSuccess: function(G) {
                        E.attr("data-need-loading", "false")
                    },
                    onError: function() {
                        dispatchAjaxError(E.children("a").next())
                    }
                })
            } else {
                if (F) {
                    $(".dropdown ul li a[data-value=" + F + "]", E).click()
                } else {
                    UpdateDataInWidget($(".stat-box-content", E))
                }
            }
        }
        return false
    });
    $(".stat-box .close").live("click", function() {
        var E = $(this).parents(".stat-box");
        if (!$(".omni-widget-popup", E).length) {
            E.hide()
        }
        return false
    });
    $(".filter-by").live("click", function() {
        $(this).siblings().removeClass("active");
        $(this).addClass("active");
        var F = $(this).data("url");
        var E = $(this).parents(".table-content");
        $.ajax({
            url: F,
            type: "GET",
            success: function(G) {
                $("table tr:first-child", E).nextAll().remove();
                $("table tr:first-child", E).after(G)
            }
        })
    });

    function q(G) {
        var F = $(G);
        var E = F.add(F.parents());
        var H = false;
        E.each(function() {
            if ($(this).css("position") === "fixed") {
                H = true;
                return false
            }
        });
        return H
    }
    $(".dropdown:not(.disabled) > a, .orders-dropdown > a").live("click", function() {
        var H = $(this),
            G = H.parent(),
            F = G.find("ul, .dropdown-wrapper").first(),
            J = F.is(":visible"),
            K = false;
        $(".dropdown").removeClass("active").not(".dropdown-parent").find("ul, .dropdown-wrapper").hide();
        $("#date-selector-popup:visible").hide();
        G.removeClass("up").removeClass("down").removeClass("right");
        if (G.children().is(".ul-scroll-dropdown-wrapper")) {
            F = G.children(".ul-scroll-dropdown-wrapper");
            K = true;
            F.find("ul").show()
        }
        if (G.find("textarea").length) {
            var E = G.find("textarea").first()[0];
            setTimeout(function() {
                E.focus();
                E.select()
            }, 0)
        }
        if (K) {
            F = G.find("ul, .dropdown-wrapper")
        }
        if (G.hasClass("orders-dropdown")) {
            $(".orders-dropdown ul").hide()
        }
        if (J) {
            F.hide()
        } else {
            F.css({
                visibility: "hidden",
                display: "block"
            });
            var I = q(H);
            if (!G.parents(".stat-box-popup-container").length) {
                if (!G.parents(".kpi-filter").length && (G.hasClass("dropdown-up") || !I && F.height() + H.offset().top + H.height() > $(document).height() || I && F.height() + H.offset().top + H.height() > $(window).height())) {
                    G.addClass("up")
                } else {
                    G.addClass("down")
                }
            } else {
                if (G.hasClass("dropdown-up") || F.height() + H.height() + H.offset().top + H.height() > (G.closest(".stat-box-wrapper").offset().top + G.closest(".stat-box-wrapper").height())) {
                    G.addClass("up")
                } else {
                    G.addClass("down")
                }
                G.closest(".stat-box-wrapper").one("click", function(M) {
                    var L = $(M.target);
                    if (!L.is(".dropdown ul > li")) {
                        $(".dropdown ul:visible").hide();
                        $(".dropdown.active").removeClass("active")
                    }
                })
            }
            if (G.hasClass("dropdown-right") || F.outerWidth() + H.offset().left > $(document).width()) {
                G.addClass("right")
            } else {
                G.removeClass("right")
            }
            F.css({
                visibility: "",
                display: "none"
            });
            H.trigger("dropdown:before");
            G.addClass("active");
            F.show()
        }
        return false
    });
    $(".dropdown .ul-scroll-dropdown-wrapper ul").live("show", function() {
        $(this).parent().show()
    });
    $(".dropdown .ul-scroll-dropdown-wrapper ul").live("hide", function() {
        $(this).parent().hide()
    });
    $(".dropdown ul > li > a:not([data-pay-send])").live("click", function(H) {
        var J = $(this),
            L = J.parent();
        if (L.hasClass("disabled") || L.hasClass("combine-order")) {
            return
        }
        H.preventDefault();
        var I = L.parent();
        if (!J.attr("data-title") && !J.parent(".dropdown").is(".dropdown-children") && !I.parent().hasClass("no-set")) {
            I.hide(function() {
                window.location = J.attr("href")
            });
            return
        }
        if (I.parent().hasClass("ul-scroll-dropdown-wrapper")) {
            var N = I.parent().prev()
        } else {
            var N = I.prev()
        }
        if (!I.parent().hasClass("no-set")) {
            if (!I.closest(".dropdown").hasClass("dropdown-multiselect")) {
                N.html(J.attr("data-title"));
                if (N.attr("title") && N.parent(".dropdown").hasClass("product-status")) {
                    N.attr("title", J.attr("data-title"))
                }
                N.attr("data-value", J.attr("data-value"));
                N.attr("title", J.attr("data-title"));
                I.children().removeClass("active");
                L.addClass("active")
            } else {
                L.toggleClass("active")
            }
        }
        if (N.parent().hasClass("funnel-filter-type-selector")) {
            N.parent().parent().addClass("chosen");
            var K = J.data("value");
            var M = J.data("title");
            N.html("<span>" + M + "</span> &mdash;");
            N.parent().parent().find("div.funnel-filter-scope-container").hide();
            currentContainer = N.parent().parent().find("div.funnel-filter-scope-container#funnel-scope-" + K);
            currentContainer.removeClass("hide").show()
        }
        if (N.parent().parent().hasClass("widget-funnel-filter") || N.parent().parent().parent().hasClass("widget-funnel-filter")) {
            var G = {};
            var F = N.parents(".widget-funnel-filter");
            var K;
            if (K = F.find("a.funnel-filter").attr("data-value")) {
                var O = {};
                F.find("a.funnel-filter-value:visible").each(function() {
                    G[$(this).parent().attr("id").replace("funnel-filter-", "")] = $(this).attr("data-value")
                });
                G.filter = K
            } else {
                G.filter = "no"
            }
            updateFunnelWidget(G)
        }
        if (!I.closest(".dropdown").hasClass("dropdown-multiselect") && !I.closest(".dropdown").hasClass("dropdown-parent")) {
            I.hide()
        }
        var E = $(".omni-widget-popup", J.parents("div.stat-box"));
        if (!E.length) {
            E = $(this).parents("div.stat-box").next().next()
        }
        if (E.length && E.hasClass("stat-box-popup") && I.parent().hasClass("timeInterval")) {
            $(".dropdown.widget-interval.timeInterval > a", E).attr("data-value", N.attr("data-value"));
            $(".dropdown.widget-interval.timeInterval > a", E).html(N.html())
        }
        J.trigger("dropdown:click");
        I.closest(".dropdown").children().filter("a").trigger("dropdown:change")
    });
    $(".dropdown .dropdown-table tr").live("click", function() {
        var E = $(this);
        E.closest(".dropdown-wrapper").hide()
    });
    $(".dropdown-hide").live("click", function() {
        $(this).closest(".dropdown-wrapper").hide()
    });
    $(".tabs-bord > li > a").live("click", function() {
        var F = $(this).parent();
        var E = $(".omni-widget-popup", $(this).parents("div.stat-box"));
        if (!F.hasClass("active")) {
            F.addClass("active").siblings("li").removeClass("active")
        } else {
            return false
        }
    });
    $(".stat-box .dropdown.widget-interval ul a, .stat-box-popup .dropdown.widget-interval ul a, .stat-box .tabs-bord.widget-interval li a").live("click", function() {
        var E = $(this);
        if (E.hasClass("omni-axis")) {
            UpdateOmniWidget(this)
        } else {
            if (E.hasClass("cancels-axis")) {
                UpdateCancelsWidget(this, E.parents(".widget-interval").hasClass("secondaryAxis") ? "numeric" : "additional")
            } else {
                if (E.hasClass("order-in-statuses-axis")) {
                    UpdateOrderInStatusesWidget(this)
                } else {
                    if (E.hasClass("manager-activity-axis")) {
                        UpdateManagersActivityWidget(this)
                    } else {
                        if (E.hasClass("manager-expiration-axis")) {
                            UpdateManagersExpirationWidget(this)
                        } else {
                            if (E.hasClass("manager-finances-axis")) {
                                UpdateManagersFinancesWidget(this)
                            } else {
                                if (E.hasClass("tasks-axis")) {
                                    UpdateTasksWidget(this)
                                } else {
                                    if (E.hasClass("calls-axis")) {
                                        UpdateCallsWidget(this)
                                    } else {
                                        if (E.hasClass("widget-communication-filter")) {
                                            UpdateCommunicationWidget(this)
                                        } else {
                                            if (E.hasClass("widget-letters-dynamic-filter")) {
                                                UpdateLettersDynamicWidget(this)
                                            } else {
                                                if (E.hasClass("widget-letters-statuses-filter")) {
                                                    UpdateLettersStatusesWidget(this)
                                                } else {
                                                    if ($(this).hasClass("gain-costs-axis")) {
                                                        UpdateGainCostsWidget(this)
                                                    } else {
                                                        if ($(this).hasClass("costs-indicator-axis")) {
                                                            UpdateCostsIndicatorWidget(this)
                                                        } else {
                                                            if ($(this).hasClass("kpi-cost-item-axis")) {
                                                                UpdateKpiCostsItemWidget(this)
                                                            } else {
                                                                UpdateDataInWidget(this)
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    });
    $(document).delegate("html, .parameters-popup-content", "click", function(F) {
        var E = $(F.target);
        if ($(".m-box-content-hide-by-outside").has(E).length > 0 || E.hasClass("m-box-content-hide-by-outside")) {
            return
        }
        if (!E.is(".dropdown ul > li > span") && !E.closest(".dropdown-wrapper").length) {
            $(".dropdown ul:visible, .dropdown .dropdown-wrapper:visible").hide();
            $(".dropdown.active").removeClass("active")
        }
        if (!E.closest(".toggle__block").length) {
            $(".toggle__block.toggle__block_open").removeClass("toggle__block_open")
        }
        $("#date-selector-popup").hide();
        $(".orders-dropdown ul").hide()
    });
    $(".dropdown.dropdown-multiselect ul:visible").live("hide", function() {
        $(this).parents(".dropdown-multiselect").trigger("multiselect:update")
    });
    var h = "rocPrevValue";
    var b = "rocTimer";
    var B = "change_realtime";
    var x = function(G) {
        var H = $(this),
            F = H[0];
        var E = H.val() + "";
        if (F[h] === E) {
            return
        }
        F[h] = E;
        clearTimeout(F[b]);
        F[b] = null;
        if (G && G.type === "keyup") {
            F[b] = setTimeout(function() {
                H.trigger(B)
            }, 700)
        } else {
            H.trigger(B)
        }
    };
    $.fn.realtimeOnChange = function(E) {
        return this.each(function() {
            var G = $(this),
                F = G[0];
            if (!G.length) {
                return
            }
            if (undefined === F[h]) {
                F[h] = G.val() + "";
                G.bind("keyup change focus", x);
                G.bind("cut paste", function(H) {
                    setTimeout(function() {
                        x.call(F, H)
                    }, 0)
                })
            }
            G.bind(B, E)
        })
    };
    $.fn.draggable = function(F) {
        function E() {
            return false
        }
        $(this).mousedown(function(L) {
            var J = $(this);
            var M = F.top;
            var K = F.bottom;
            var N = J.offset().top;
            var I = L.pageY - N;
            J.css({
                "z-index": 2,
                opacity: 0.7
            });
            var H = function(P) {
                var O = P.pageY - I;
                if (O < M) {
                    J.offset({
                        top: M
                    });
                    if (J.prev(".draggable-bar:not(.inactive)").length > 0) {
                        J.insertBefore(J.prev(".draggable-bar:not(.inactive)").css({
                            top: -J.height()
                        }).animate({
                            top: 0
                        }, 100))
                    }
                } else {
                    if ((O + J.height()) > K) {
                        J.offset({
                            top: K - J.height()
                        });
                        if (J.next(".draggable-bar:not(.inactive)").length > 0) {
                            J.insertAfter(J.next(".draggable-bar:not(.inactive)").css({
                                top: J.height()
                            }).animate({
                                top: 0
                            }, 100))
                        }
                    } else {
                        J.offset({
                            top: O
                        });
                        if (N - O > J.height() - 1) {
                            J.insertBefore(J.prev(".draggable-bar:not(.inactive)").css({
                                top: -J.height()
                            }).animate({
                                top: 0
                            }, 100));
                            J.css({
                                top: 0
                            });
                            N = J.offset().top;
                            O = P.pageY - I;
                            I = P.pageY - N
                        } else {
                            if (O - N > J.height() - 1) {
                                J.insertAfter(J.next(".draggable-bar:not(.inactive)").css({
                                    top: J.height()
                                }).animate({
                                    top: 0
                                }, 100));
                                J.css({
                                    top: 0
                                });
                                N = J.offset().top;
                                O = P.pageY - I;
                                I = P.pageY - N
                            }
                        }
                    }
                }
            };
            var G = function() {
                document.onmousemove = null;
                document.onmouseup = null;
                document.oncontextmenu = null;
                document.onmousedown = null;
                window.onblur = null;
                J.animate({
                    top: 0
                }, 100, function() {
                    J.attr("style", "")
                })
            };
            document.onmousemove = H;
            document.onmouseup = G;
            document.oncontextmenu = G;
            document.onmousedown = E;
            window.onblur = G
        })
    };
    $(".draggable-bar .tr-delete").live("click", function() {
        var F = $(this).parents(".draggable-bar");
        var G = F.hasClass("funnel-bar");
        if (G) {
            var E = F.parents(".widget-settings")
        }
        F.animate({
            opacity: 0,
            height: 0
        }, 300, function() {
            F.remove();
            if (G) {
                funnelHeight(E)
            }
        });
        return false
    });
    $("#parse-address-btn").live("click", function() {
        var I = $("#city-text input").val();
        var F = $("#address-text textarea").val();
        var H = F,
            G = $(this),
            E = /^ד(\.|מנמה|מנ|מנ\.|-ה)?\s+/;
        if (I != "") {
            if (null == H.match(escapeRegExp(I))) {
                cityResult = E.exec($.trim(I));
                H = (cityResult ? "" : "ד. ") + $.trim(I.replace(/\(.+?\)/, "")) + ", " + H
            }
        }
        H = H.replace("\n", " ").replace("\r", " ");
        if (H != "") {
            G.parent().addClass("loading")
        }
        $.ajax({
            url: "//geocode-maps.yandex.ru/1.x/",
            data: {
                format: "json",
                geocode: H
            },
            dataType: "jsonp",
            success: function(P) {
                var X = /ךג(אנעטנא)?\.?\s*(\d+)/,
                    V = /ן(מהתוח|-|-ח|מה-)?ה\s*(\d+)/,
                    O = /‎ע(.|אז)?\s*(\d+)/,
                    M = /(\d+)\s*ן(מהתוח|-|-ח|מה-)?ה/,
                    Q = /(\d+)\s*‎ע(.|אז)?/,
                    W = /(\d+ֱ?)(\/|ס|ך)([\wא-ÿְ-ß0-9]+)/,
                    S = /(\d{6})/,
                    T = new Object();
                var J = S.exec(H);
                if (J && J.length > 1) {
                    T.index = J[1]
                }
                var L = X.exec(H);
                if (L && L.length > 2) {
                    T.flat = L[2]
                }
                var K = V.exec(H);
                if (K && K.length > 2) {
                    T.block = K[2]
                } else {
                    K = M.exec(H);
                    if (K && K.length > 2) {
                        T.block = K[1]
                    }
                }
                var R = O.exec(H);
                if (R && R.length > 2) {
                    T.floor = R[2]
                } else {
                    R = Q.exec(H);
                    if (R && R.length > 2) {
                        T.floor = R[1]
                    }
                }
                if (P.response["GeoObjectCollection"]["metaDataProperty"]["GeocoderResponseMetaData"]["found"] > 0) {
                    var N = P.response["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["metaDataProperty"]["GeocoderMetaData"];
                    if ((countryCode = recarray(N, "CountryNameCode")) != null) {
                        T.country = countryCode
                    }
                    if (N.kind == "house" || N.kind == "street") {
                        if ((region = recarray(N, "AdministrativeAreaName")) != null) {
                            subRegion = recarray(N, "SubAdministrativeAreaName");
                            if (subRegion != null && N.Address && Array.isArray(N.Address["Components"])) {
                                subRegion = N.Address["Components"].find(function(Y) {
                                    return Y.kind == "province" && Y.name == subRegion
                                });
                                if (typeof subRegion != "undefined") {
                                    region = subRegion.name
                                }
                            }
                            T.region = region
                        }
                        locality = recarray(N, "Locality");
                        if (locality != null) {
                            T.city = locality.LocalityName;
                            if ((street = recarray(locality, "ThoroughfareName")) != null) {
                                T.street = street
                            } else {
                                if ((street = recarray(locality, "DependentLocalityName")) != null) {
                                    T.street = street
                                }
                            }
                            if ((building = recarray(locality, "PremiseNumber")) != null) {
                                T.building = building;
                                houseResult = W.exec(T.building);
                                if (houseResult && houseResult.length > 1) {
                                    T.building = houseResult[1];
                                    T.house = houseResult[3]
                                }
                            }
                        }
                        var U = P.response["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["Point"]["pos"];
                        $.ajax({
                            url: "//geocode-maps.yandex.ru/1.x/",
                            data: {
                                format: "json",
                                geocode: U,
                                kind: "metro"
                            },
                            dataType: "jsonp",
                            success: function(aa) {
                                if (aa.response["GeoObjectCollection"]["metaDataProperty"]["GeocoderResponseMetaData"]["found"] > 0) {
                                    var Y = aa.response["GeoObjectCollection"]["featureMember"][0]["GeoObject"]["name"];
                                    if (Y.length > 0) {
                                        var Z = /לוענמ(.*)/;
                                        var ab = Z.exec(Y);
                                        if (ab && ab.length > 1) {
                                            $("#metro-text input").val(ab[1]).addClass("important-auto-data")
                                        }
                                    }
                                }
                            }
                        })
                    } else {
                        T.error = _translate("message.parse_address_fail")
                    }
                } else {
                    T.error = _translate("message.parse_address_fail")
                }
                if (!T.error) {
                    $("#delivery-address-form").data("addressForm").setAddressToForm(T);
                    $("#delivery-address-parse-error").hide();
                    $("#delivery-source-address").html("<h5>" + _translate("info.primary_address") + "</h5><p>" + H + "</p>").show()
                } else {
                    $("#delivery-address-parse-error").html(T.error).change();
                    $("#delivery-address-parse-error").show();
                    $("#delivery-source-address").hide()
                }
                G.trigger("addressParsed").parent().removeClass("loading");
                $("#delivery-address-form").addClass("with-addr-text")
            },
            error: function() {
                $("#parse-error").html(_translate("message.parse_address_fail"));
                $("#parse-error").show();
                $("#delivery-source-address").hide();
                G.parent().removeClass("loading")
            }
        })
    });
    $("#intaro_crmbundle_ordertype_deliveryAddress_text, #crm_customer_edit_address_text").bind("input propertychange change", function() {
        parseBtnVisible()
    });

    function u(E) {
        E.find(".datepicker:not(.datepicker-custom),.date:not(.datepicker-custom)").datepicker({
            dateFormat: _getDateFormatString()
        });
        E.find(".switching-datepicker").datepicker({
            dateFormat: _getDateFormatString(),
            showOtherMonths: true,
            selectOtherMonths: true,
            onSelect: function(G, F) {
                var H = $(this);
                H.parent().find(".absolute-date").val($.datepicker.formatDate("yy-mm-dd", new Date(F.selectedYear, F.selectedMonth, F.selectedDay)));
                H.parent().find(".relative-date").val("");
                if (H.hasClass("ignore-year-datepicker")) {
                    H.datepicker("option", "dateFormat", _getDateFormatString(true))
                } else {
                    H.datepicker("option", "dateFormat", _getDateFormatString())
                }
            },
            beforeShow: function(F, H) {
                var G = $("#ui-datepicker-div");
                var I = $(F);
                G.addClass("switching");
                d();
                I.addClass("active-datepicker");
                if (I.hasClass("ignore-year-datepicker")) {
                    G.addClass("hide-year")
                } else {
                    G.removeClass("hide-year")
                }
            },
            onClose: function(G, F) {
                $("#ui-datepicker-div").removeClass("rel-dates-show").removeClass("switching").removeClass("hide-year");
                $(this).removeClass("active-datepicker").prop("disabled", false)
            }
        })
    }
    $.fn.initJsControls = function() {
        var E = $(this);
        E.each(function() {
            var F = $(this);
            if (!F.is("select")) {
                F = F.find("select:not(.hide)")
            }
            F.not(".status-group").chosen()
        });
        u(E);
        E.find(".slider-price:not(.disabled)").sliderPrice()
    };
    $("body").initJsControls();
    $("#ui-datepicker-div").delegate(".ui-datepicker-prev, .ui-datepicker-next", "click", d);

    function d() {
        if (!$("#ui-datepicker-div").hasClass("switching")) {
            return
        }
        clearTimeout(d.timer);
        if ($("#ui-datepicker-div .ui-datepicker-calendar").is(":visible")) {
            var E = '<div class="rel-dates-list">';
            E += '<p class="wt-smoke">' + _translate("datepicker.back") + "</p>";
            E += '<p class="relative">';
            E += '<a data-value="-1 year" href="#">' + _translate("datepicker.1year") + "</a>";
            E += '<a data-value="-6 months" href="#">' + _translate("datepicker.6months") + "</a>";
            E += '<a data-value="-3 months" href="#">' + _translate("datepicker.3months") + "</a>";
            E += '<a data-value="-2 months" href="#">' + _translate("datepicker.2months") + "</a>";
            E += '<a data-value="-1 month" href="#">' + _translate("datepicker.1month") + "</a>";
            E += '<a data-value="-3 weeks" href="#">' + _translate("datepicker.3weeks") + "</a>";
            E += '<a data-value="-2 weeks" href="#">' + _translate("datepicker.2weeks") + "</a>";
            E += '<a data-value="-1 week" href="#">' + _translate("datepicker.1week") + "</a>";
            E += '<a data-value="-6 days" href="#">' + _translate("datepicker.6days") + "</a>";
            E += '<a data-value="-5 days" href="#">' + _translate("datepicker.5days") + "</a>";
            E += '<a data-value="-4 days" href="#">' + _translate("datepicker.4days") + "</a>";
            E += '<a data-value="-3 days" href="#">' + _translate("datepicker.3days") + "</a>";
            E += '<a data-value="-2 days" href="#">' + _translate("datepicker.2days") + "</a>";
            E += '<a data-value="-1 day" href="#">' + _translate("datepicker.1day") + "</a>";
            E += "</p>";
            E += '<p class="relative"><a data-value="today" href="#">' + _translate("datepicker.today") + "</a></p>";
            E += '<p class="wt-smoke">' + _translate("datepicker.forward") + "</p>";
            E += '<p class="relative">';
            E += '<a data-value="+1 day" href="#">' + _translate("datepicker.1day") + "</a>";
            E += '<a data-value="+2 days" href="#">' + _translate("datepicker.2days") + "</a>";
            E += '<a data-value="+3 days" href="#">' + _translate("datepicker.3days") + "</a>";
            E += '<a data-value="+4 days" href="#">' + _translate("datepicker.4days") + "</a>";
            E += '<a data-value="+5 days" href="#">' + _translate("datepicker.5days") + "</a>";
            E += '<a data-value="+6 days" href="#">' + _translate("datepicker.6days") + "</a>";
            E += '<a data-value="+1 week" href="#">' + _translate("datepicker.1week") + "</a>";
            E += '<a data-value="+2 weeks" href="#">' + _translate("datepicker.2weeks") + "</a>";
            E += '<a data-value="+3 weeks" href="#">' + _translate("datepicker.3weeks") + "</a>";
            E += '<a data-value="+1 month" href="#">' + _translate("datepicker.1month") + "</a>";
            E += '<a data-value="+2 months" href="#">' + _translate("datepicker.2months") + "</a>";
            E += '<a data-value="+3 months" href="#">' + _translate("datepicker.3months") + "</a>";
            E += '<a data-value="+6 months" href="#">' + _translate("datepicker.6months") + "</a>";
            E += '<a data-value="+1 year" href="#">' + _translate("datepicker.1year") + "</a>";
            E += "</p>";
            E += "</div>";
            E += '<a class="rel-reset-btn" href="#"><i class="icon-times"></i>' + _translate("link.reset") + "</a>";
            E += '<a class="rel-dates-btn" href="#">' + _translate("datepicker.rel") + "</a>";
            $("#ui-datepicker-div").append(E)
        } else {
            d.timer = setTimeout(d, 10)
        }
    }
    $("#ui-datepicker-div .rel-dates-list .relative a").live("click", function(F) {
        F.preventDefault();
        var E = $(this).data("value")[0];
        if (E == "-" || E == "+") {
            E = E.replace("-", "–")
        } else {
            E = ""
        }
        $(".active-datepicker").parent().find(".absolute-date").val("");
        $(".active-datepicker").parent().find(".relative-date").val($(this).data("value"));
        $(".active-datepicker").val(E + $(this).text());
        $(".active-datepicker").datepicker("hide")
    });
	
	
	
    $("#ui-datepicker-div .rel-dates-btn").live("click", function(G) {
        G.preventDefault();
        var E = $("#ui-datepicker-div"),
            F = $(this);
        if (E.hasClass("rel-dates-show")) {
            E.removeClass("rel-dates-show");
            F.text(_translate("datepicker.rel"));
            $(".active-datepicker").prop("disabled", false)
        } else {
            E.addClass("rel-dates-show");
            F.text(_translate("datepicker.abs"));
            $(".active-datepicker").prop("disabled", "disabled")
        }
    });
    $("#ui-datepicker-div .rel-reset-btn").live("click", function(E) {
        E.preventDefault();
        $(".active-datepicker").val("").parent().find(".absolute-date, .relative-date").val("");
        $(".active-datepicker").datepicker("hide")
    });
    $(".date-icon").live("click", function() {
        if (!$("#ui-datepicker-div:visible").length) {
            $(this).prev(".datepicker, .switching-datepicker").focus()
        }
    });
    if ($("#delivery-address-form").length) {
        var a = new AddressForm($("#delivery-address-form"));
        a.init()
    }
    if ($("#oas-communication-widget").length && typeof RuleWidget == "function") {
        var g = new RuleWidget($("#oas-communication-widget"));
        g.initCommunication()
    }
    parseBtnVisible();
    if (typeof ORDER_OPTIONS != "undefined" && typeof ORDER_OPTIONS.parseAddress != "undefined" && ORDER_OPTIONS.parseAddress && $("#parse-address-btn").is(":visible")) {
        $("#parse-address-btn").click()
    }
    $(".popup-open").live("click", function() {
        var E = $(this).next();
        E.intaroPopup();
        $(".scroll-pane").jScrollPane({
            showArrows: true
        })
    });
    var m = $('button[name="saveAndBackBtn"]');
    m.click(function(J) {
        J.preventDefault();
        var F = window.localStorage.getItem("lastListFilters");
        F = JSON.parse(F);
        if (!F) {
            F = []
        }
        var E = $('[name="saveAndBack"]');
        var H = $(this).closest("form");
        var G = H.data("form-list-url");
        if (!G) {
            G = $(".save-box").find("a.back").attr("href")
        }
        if (isLocalStorageAvailable() && F.length > 0) {
            for (var I in F) {
                if (F[I].pathname == G) {
                    E.val(G + "?" + F[I].queryString)
                }
            }
        }
        if (!E.val()) {
            E.val(G)
        }
        H.find(".btn-save").click()
    });
    $(".datepicker").each(function() {
        addDateIcon($(this))
    });
    $.each(["show", "hide"], function(E, G) {
        var F = $.fn[G];
        $.fn[G] = function() {
            this.trigger(G);
            return F.apply(this, arguments)
        }
    });
    tabsWidth();
    $(".stat-box-popup .close-link").live("click", function() {
        $(this).parents(".stat-box-popup").find(".close").click();
        return false
    });
    $("#crm_customer_edit_contragent_contragentType, #intaro_crmbundle_ordertype_contragent_contragentType").change(function() {
        var F = $(this).val();
        $("#legal-details-form .input-group").each(function() {
            if ($(this).find(":input[data-group]").length > 0) {
                $(this).hide()
            }
        });
        $('#legal-details-form :input[data-group*="' + F + '"]').parents(".input-group").show();
        var E = $("#bank-details-form");
        if (E.is('[data-group*="' + F + '"]')) {
            E.parents(".bk").show()
        } else {
            E.parents(".bk").hide()
        }
    }).change();
    $("#user-status > a").click(function() {
        var H = $(this);
        var I = H.data("url");
        var G = H.data("status");
        var F = $("#current-status");
        var E = H.text();
        if (!I || !G) {
            return false
        }
        F.css("opacity", "0.6").attr("class", "").addClass(G).addClass("status-text").text($(this).text()).css("opacity", "1");
        $(".user-top-menu").hide();
        $.ajax({
            url: I,
            type: "POST",
            data: {
                status: G
            },
            success: function(J) {
                if (J != "ok") {
                    alert(CRM_ERROR_MESSAGE)
                }
            }
        });
        return false
    });
    var w = $("#crm_customer_edit_contragent_contragentType, #intaro_crmbundle_ordertype_contragent_contragentType").val();
    if ($(".save-box").is(":visible")) {
        $(".main-form .m-box, .m-box.form, .m-filter + .container > .m-box").last().addClass("m-box_last");
        if ($(".save-box.save-box_with-btns").hasClass("saved")) {
            setTimeout(function() {
                $(".save-box.save-box_with-btns").find(".save-box__msg:not(.save-box__msg-user)").fadeOut(400, function() {
                    $(this).closest(".save-box.save-box_with-btns").removeClass("saved")
                })
            }, 3000)
        }
    }
    $(".save-box__close").live("click", function() {
        var F = $(this).closest(".save-box");
        var E = $(this).closest(".save-box__msg");
        if (!F.find("input[type=submit].btn").length) {
            F.fadeOut("fast");
            $(".m-box_last").removeClass("m-box_last")
        } else {
            E.fadeOut("fast")
        }
    });
    $(".toggle__btn").live("click", function(H) {
        H.preventDefault();
        H.stopPropagation();
        var E = $(this);
        var F = E.next(".toggle__drop");
        var G = E.closest(".toggle__block");
        if (!G.hasClass("toggle__block_open")) {
            $(".toggle__block_open").removeClass("toggle__block_open");
            G.addClass("toggle__block_open");
            if ((F.outerWidth() + G.offset().left) > $(document).width()) {
                G.addClass("toggle__block_right")
            } else {
                G.removeClass("toggle__block_right")
            }
        } else {
            G.removeClass("toggle__block_open")
        }
    });
    $(".input-field-phone").change();
    $(".save-box .js-state_operaion_update").click(function(E) {
        $('input[name $= "[updateStateDate]"]').val((new Date()).toISOString())
    });
    $(".save-box .js-state_operaion_reset").click(function(E) {
        E.preventDefault();
        E.stopPropagation();
        window.location.href = window.location.href.split("#")[0]
    });
    $("#history-popup-link-conflict").live("click", function(G) {
        G.preventDefault();
        var E = $("#customer-history-popup-link, #status-history-popup-link");
        var F = $("#customer-history-popup, #order-history-popup");
        E.click();
        F.one("afterOpen", function() {
            var I = new Date($('input[name $= "[updateStateDate]"]').val());
            var H = false;
            $(this).find("table tr").each(function() {
                if ($(this).find("td").length == 6) {
                    var K = new Date($(this).find("td:last").data("date"));
                    if (K > I) {
                        var J = $(this);
                        setTimeout(function() {
                            J.addClass("transition").addClass("important-auto-data")
                        }, 1);
                        H = true
                    } else {
                        H = false
                    }
                } else {
                    if (H) {
                        var J = $(this);
                        setTimeout(function() {
                            J.addClass("transition").addClass("important-auto-data")
                        }, 1)
                    }
                }
            })
        })
    });
    $('[data-toggle="hide"]').live("click", function() {
        $($(this).data("target")).toggleClass("hide")
    });
    $('[data-behaviour="open-popup"]').click(onPopupOpen)
});
$(window).resize(function() {
    if (typeof(widgetHeight) === "function") {
        widgetHeight()
    }
    if (typeof(respondKpi) === "function") {
        respondKpi()
    }
    tabsWidth();
    $(".stat-box .widget-settings").each(function() {
        if ($(this).is(":visible") && $(this).parent().hasClass("segments")) {
            var a = $(this).find(".widget-settings-inputs").outerHeight() + $(this).find(".save-bar").outerHeight() + 20;
            $(this).parent().animate({
                height: a
            }, 500)
        }
    })
});

function place(b) {
    var a = 0;
    $("#s-tabs > li").each(function() {
        a = a + $(this).width();
        return a
    });
    return listDiffWidth = b - a
}

function tabsWidth() {
    if ($("#s-tabs").length) {
        var e = $("#s-tabs").width();
        var a = $("#s-tabs > li").length;
        place(e);
        if (listDiffWidth < 0) {
            $("#s-tabs > li").css("max-width", 100 / a + "%");
            place(e);
            var d = e / a;
            var b = 0;
            $("#s-tabs > li").each(function() {
                if ($(this).width() >= d) {
                    $(this).addClass("big");
                    $(this).attr("title", $(this).find("a").text());
                    ++b
                }
            });
            var c = listDiffWidth / b;
            $("#s-tabs > li.big").each(function() {
                var f = $(this).width() + c;
                $(this).css("max-width", f)
            })
        } else {
            if (listDiffWidth > 0 && e > 1000) {
                $("#s-tabs > li").css("max-width", "")
            } else {
                return
            }
        }
    }
}
var addDateIcon = function(a) {
    $dateIcon = a.next(".date-icon");
    if (!$dateIcon.length) {
        $span = $("<span>").addClass("date-icon");
        a.after($span)
    }
};

function parseBtnVisible() {
    var a = $.trim($("#address-text textarea").val());
    if (a.length > 0) {
        $("#parse-address-btn").show().parent().removeClass("none")
    } else {
        $("#parse-address-btn").hide().parent().addClass("none")
    }
}

function recarray(a, b) {
    for (k in a) {
        if (k == b) {
            return a[k]
        } else {
            if (typeof a[k] == "object") {
                var c = recarray(a[k], b);
                if (c !== null) {
                    return c
                }
            }
        }
    }
    return null
}

function buildHttpQuery(a) {
    result = Object.keys(a).reduce(function(c, b) {
        if (c != "") {
            c += "&"
        }
        return c + escape(b) + "=" + escape(a[b])
    }, "");
    return result
}

function changeUrlParameter(c, b, g) {
    var a = c.indexOf("?");
    if (a == -1) {
        c += "?" + b + "=" + g
    } else {
        baseUrl = c.substr(0, a);
        var f = false;
        var e = c.substr(a + 1);
        e = e.split("&");
        if (e.length == 1 && e[0] == "") {
            e = []
        }
        for (var d = 0; d < e.length; d++) {
            if (e[d].substr(0, b.length) == b) {
                e[d] = b + "=" + g;
                f = true;
                break
            }
        }
        if (!f) {
            e.push(b + "=" + g)
        }
        c = baseUrl + "?" + e.join("&")
    }
    return c
}
var escapeRegExp = function(a) {
    return a.replace(/[\-\[\]\/\{\}\(\)\*\+\?\.\\\^\$\|]/g, "\\$&")
};

function AddressForm(a) {
    this.iconDisconnected = '<a class="region__info region__info_error" href="javascript:void(0);" title="' + _translate("info.not_associated_geohelper") + '"></a>';
    this.$formContainer = a;
    this.$country;
    this.$region;
    this.$city;
    this.$street;
    this.countryRegionList = null;
    this.regionLoading = null;
    this.init = function() {
        if (this.$formContainer == undefined || !this.$formContainer.length) {
            return false
        }
        this.$formContainer.data("addressForm", this);
        var b = this;
        this.refreshVisibility();
        this.$country = $("select[data-country-value]");
        this.$region = this.$formContainer.find('input[name $= "[region]"]');
        this.$region.data("previous-field", this.$country);
        this.$city = this.$formContainer.find('input[name $= "[city]"]');
        this.$city.data("previous-field", this.$region);
        this.$street = this.$formContainer.find('input[name $= "[street]"]');
        this.$street.data("previous-field", this.$city);
        if (globalSettings.geohelper != undefined && globalSettings.geohelper.useAutocomplete) {
            enableAutocomplete();
            this.$country.change(function(c) {
                b.onCountryChange()
            });
            this.$region.autocomplete("option", "valueField").change(function(c, d) {
                b.onRegionIdChange(d)
            });
            this.checkFieldConnected(this.$region);
            if (this.$country.val() != "") {
                this.loadRegions(this.$country.val(), true)
            }
            this.$city.autocomplete("option", "valueField").change(function(c, d) {
                b.onCityIdChange(d)
            });
            this.checkFieldConnected(this.$city);
            this.$city.autocomplete("option", "source", function(d, c) {
                b.cityAutocompleteSourceCallback(d, c)
            });
            this.$street.autocomplete("option", "valueField").change(function(c, d) {
                b.onStreetIdChange(d)
            });
            this.checkFieldConnected(this.$street);
            this.$street.autocomplete("option", "source", function(d, c) {
                b.streetAutocompleteSourceCallback(d, c)
            });
            if (!this.autocompleteValueSpecified(this.$city)) {
                this.$street.autocomplete("option", "disabled", true)
            }
            this.$formContainer.delegate("a.region__info_error", "click", function(d) {
                d.preventDefault();
                var c = $(this).siblings("input.autocomplete");
                if (c.length == 0) {
                    return
                }
                if (c.data("previous-field") && c.data("previous-field").siblings("a.region__info_error").length > 0 && c.data("previous-field").val() != "") {
                    c.data("previous-field").siblings("a.region__info_error").click();
                    return
                }
                if (c.autocomplete("option", "disabled")) {
                    return
                }
                invokeAutocompleteWithTerm(c, c.val())
            });
            $("#find-post-code-btn").click(function(c) {
                c.preventDefault();
                b.findPostCode()
            })
        } else {
            this.$formContainer.find('input[name $= "[region]"]').removeClass("autocomplete");
            this.$formContainer.find('input[name $= "[city]"]').removeClass("autocomplete")
        }
    };
    this.cityAutocompleteSourceCallback = function(e, d) {
        url = Routing.generate("crm_dictionary_geohelper_city");
        if (this.cityXhr) {
            this.cityXhr.abort()
        }
        var c = this;
        var b = {};
        if (c.autocompleteValueSpecified(c.$region)) {
            b.regionId = c.$region.autocomplete("option", "valueField").val()
        } else {
            b.countryIso = c.$country.val()
        }
        b.name = e.term;
        this.cityXhr = $.ajax({
            url: url,
            data: {
                filter: b,
                order: {
                    by: "population",
                    dir: "desc"
                }
            },
            dataType: "json",
            success: function(f) {
                if (f.result == undefined) {
                    d(f);
                    return
                }
                f = f.result.map(function(l) {
                    var j = null;
                    description = [];
                    if (l.localityType != undefined) {
                        if (l.localityType.localizedNamesShort != undefined && l.localityType.localizedNamesShort[f.language] != undefined) {
                            j = l.localityType.localizedNamesShort[f.language]
                        } else {
                            if (l.localityType.name != undefined) {
                                j = l.localityType.name
                            }
                        }
                        if (l.localityType.name != undefined) {
                            description.push(l.localityType.name)
                        }
                    }
                    if (l.area != undefined) {
                        description.push(l.area)
                    }
                    if (!c.autocompleteValueSpecified(c.$region) && l.regionId != undefined) {
                        $region = c.$formContainer.find('input[name $= "[region]"]');
                        regionList = $region.autocomplete("option", "source");
                        for (var h = 0; h < regionList.length; h++) {
                            if (regionList[h].value == l.regionId) {
                                description.push(regionList[h].label);
                                break
                            }
                        }
                    }
                    var g = l.name;
                    if (j) {
                        g = j + " " + g
                    }
                    var m = {
                        label: g,
                        value: l.id,
                        description: description.join(", "),
                        locality: j,
                        externalIds: l.externalIds,
                        codes: l.codes
                    };
                    return m
                });
                d(f)
            },
            error: function() {
                d([])
            }
        })
    };
    this.streetAutocompleteSourceCallback = function(e, b) {
        var d = this.$formContainer.find('input[name $= "[city]"]');
        var c = Routing.generate("crm_dictionary_geohelper_street");
        if (this.streetXhr) {
            this.streetXhr.abort()
        }
        this.streetXhr = $.ajax({
            url: c,
            data: {
                filter: {
                    cityId: d.autocomplete("option", "valueField").val(),
                    name: e.term
                }
            },
            dataType: "json",
            success: function(f) {
                if (f.result == undefined) {
                    b(f);
                    return
                }
                f = f.result.map(function(j) {
                    var h = null;
                    if (j.localityType != undefined) {
                        if (j.localityType.localizedNamesShort != undefined && j.localityType.localizedNamesShort[f.language] != undefined) {
                            h = j.localityType.localizedNamesShort[f.language]
                        } else {
                            if (j.localityType.name != undefined) {
                                h = j.localityType.name
                            }
                        }
                    }
                    var g = j.name;
                    if (h) {
                        g = h + " " + g
                    }
                    var l = {
                        label: g,
                        value: j.id,
                        description: h,
                        locality: h
                    };
                    return l
                });
                b(f)
            },
            error: function() {
                b([])
            }
        })
    };
    this.loadRegions = function(d, c) {
        $region = this.$formContainer.find('input[name $= "[region]"]');
        $city = this.$formContainer.find('input[name $= "[city]"]');
        if (this.countryRegionList == d) {
            return
        }
        $region.autocomplete("option", "source", []);
        $region.parent().addClass("loading");
        if (this.regionLoading) {
            this.regionLoading.abort()
        }
        var b = this;
        this.regionLoading = $.get(Routing.generate("crm_dictionary_geohelper_region"), {
            filter: {
                countryIso: d
            }
        }).success(function(f) {
            if (f.result == undefined) {
                return
            }
            f = f.result.map(function(j) {
                var h = {
                    value: j.id,
                    label: j.name,
                    data: j
                };
                if (j.timezone != undefined) {
                    h.description = j.timezone
                }
                return h
            });
            if (f.length > 0) {
                $region.autocomplete("option", "source", f);
                $region.autocomplete("option", "disabled", false);
                $city.autocomplete("option", "disabled", false);
                if (c !== true && $region.val() != "" && $region.autocomplete("option", "valueField").val() == "") {
                    var e = $("select[data-country-value]");
                    var g = b.findOneRegion(e.val(), $region.val());
                    if (g) {
                        $region.val(g.name);
                        $region.autocomplete("option", "valueField").val(g.id).trigger("change", [{
                            data: g
                        }])
                    }
                }
                b.countryRegionList = d
            } else {
                $region.autocomplete("option", "disabled", true);
                $city.autocomplete("option", "disabled", true)
            }
        }).always(function() {
            $region.parent().removeClass("loading")
        })
    };
    this.findOneRegion = function(e, d) {
        var c = this.$formContainer.find('input[name $= "[region]"]');
        c.parent().addClass("loading");
        parameters = {
            countryIso: e,
            name: d
        };
        var b = false;
        $.ajax({
            url: Routing.generate("crm_dictionary_geohelper_region"),
            data: {
                filter: parameters
            },
            async: false,
            success: function(f) {
                if (f.result == undefined || f.result.length != 1) {
                    b = false
                } else {
                    b = f.result[0]
                }
            },
            complete: function() {
                c.parent().removeClass("loading")
            }
        });
        return b
    };
    this.onCountryChange = function() {
        var d = $("select[data-country-value]");
        var b = this.$formContainer.find('input[name $= "[region]"]');
        var c = this.$formContainer.find('input[name $= "[city]"]');
        b.autocomplete("option", "source", []);
        this.countryRegionList = null;
        b.autocomplete("option", "valueField").val("").change();
        c.autocomplete("option", "valueField").val("").change();
        if (d.val() == "") {
            b.autocomplete("option", "disabled", true);
            c.autocomplete("option", "disabled", true);
            return
        }
        this.loadRegions(d.val())
    };
    this.onRegionIdChange = function(h, m) {
        if (typeof m == "undefined" || m !== false) {
            m = true
        }
        var d = this.$formContainer.find('input[name $= "[region]"]');
        var b = this.$formContainer.find('input[name $= "[city]"]');
        var g = $("#region-text").find(".phone-info");
        this.checkFieldConnected(d);
        if (m) {
            b.autocomplete("option", "valueField").val("").change()
        }
        g.find(".phone-info__time").text("");
        Time.removePhoneTime(g, false);
        if (d.autocomplete("option", "valueField").val() == "") {
            if (this.$country.val() == "") {
                b.autocomplete("option", "disabled", true)
            } else {
                var n = $("select[data-country-value]");
                b.autocomplete("option", "disabled", false)
            }
            return
        }
        if (h != undefined && h.data.timezoneOffset != undefined) {
            $("#address-time-offset input").val(h.data.timezoneOffset)
        } else {
            $("#address-time-offset input").val("")
        }
        if ($("#address-time-offset input").val() != "") {
            var f = $("#address-time-offset input").val();
            var c = new Date();
            var l = c.getTime();
            var p = l + c.getTimezoneOffset() * 60000 + f * 1000;
            c = new Date(p);
            var o = c.getHours();
            if (o < 10) {
                o = "0" + o
            }
            var e = c.getMinutes();
            if (e < 10) {
                e = "0" + e
            }
            var j = f / 3600;
            g.find(".phone-info__time").text(o + ":" + e);
            g.find(".phone-info__zone").text("UTC " + ((j > 0) ? "+" + j : j));
            Time.initPhoneTime(g.find(".phone-info__time"))
        }
        b.autocomplete("option", "disabled", false);
        if (b.val() != "" && b.autocomplete("option", "valueField").val() == "") {
            invokeAutocompleteWithTerm(b, b.val())
        }
    };
    this.checkFieldConnected = function(b) {
        b.siblings("a.region__info_error").remove();
        if (b.val() != "" && b.autocomplete("option", "valueField").val() == "") {
            b.after(this.iconDisconnected)
        }
    };
    this.onCityIdChange = function(f) {
        this.$street.autocomplete("option", "valueField").val("");
        this.checkFieldConnected(this.$city);
        if (this.$city.autocomplete("option", "valueField").val() == "") {
            this.$street.autocomplete("option", "disabled", true);
            $("#find-post-code-btn").parent().hide();
            $('input[name $= "[cityType]"]').val("")
        } else {
            this.$street.autocomplete("option", "disabled", false);
            $("#find-post-code-btn").parent().show();
            if (f.locality != undefined && f.locality != "") {
                $('input[name $= "[cityType]"]').val(f.locality).change()
            }
            if (this.$street.val() != "" && this.$street.autocomplete("option", "valueField").val() == "") {
                invokeAutocompleteWithTerm(this.$street, this.$street.val())
            }
        }
        this.checkFieldConnected(this.$street);
        if (this.$city.autocomplete("option", "valueField").val() == "" || this.autocompleteValueSpecified(this.$region)) {
            return
        }
        var e = $("select[data-country-value]");
        var b = this.$formContainer.find('input[name $= "[region]"]');
        var d = {
            cityId: this.$city.autocomplete("option", "valueField").val(),
            countryIso: e.val()
        };
        var c = this;
        $.get(Routing.generate("crm_dictionary_geohelper_region"), {
            filter: d
        }).success(function(h) {
            if (!h.success || h.result == undefined || h.result.length !== 1) {
                return
            }
            b.val(h.result[0].name);
            b.autocomplete("option", "valueField").val(h.result[0].id);
            var g = h.result[0];
            g.data = g;
            c.onRegionIdChange(g, false)
        })
    };
    this.onStreetIdChange = function(b) {
        this.checkFieldConnected(this.$street);
        if (this.$street.autocomplete("option", "valueField").val() == "") {
            $('input[name $= "[streetType]"]').val("").change()
        } else {
            if (b.locality != undefined && b.locality != "") {
                $('input[name $= "[streetType]"]').val(b.locality).change()
            }
        }
    };
    this.findPostCode = function() {
        var g = {
            filter: {}
        };
        var e = this.$formContainer.find('input[name $= "[city]"]');
        if (e.autocomplete("option", "valueField").val() != "") {
            g.filter.cityId = e.autocomplete("option", "valueField").val()
        }
        var c = this.$formContainer.find('input[name $= "[street]"]');
        if (c.autocomplete("option", "valueField").val() != "") {
            g.filter.streetId = c.autocomplete("option", "valueField").val()
        }
        if (g.filter.cityId == undefined && g.filter.streetId == undefined) {
            return false
        }
        var b = $("#index-text input"),
            h = $("#index-text .ig-control_dimension");
        isFound = {
            val: false
        };
        h.addClass("loading");
        var j = function(l, m) {
            $.get(Routing.generate("crm_dictionary_geohelper_post_code"), l).success(function(n) {
                if (!m.val) {
                    b.val("")
                }
                if (!n.success || n.result == undefined) {
                    return
                }
                m.val = true;
                b.val(n.result)
            }).always(function() {
                h.removeClass("loading")
            })
        };
        var d = $("#building-text input").val();
        if (d != "") {
            g.filter.houseNumber = d
        }
        var f = $("#house-text input").val();
        if (f != "") {
            g.filter.housingNumber = f;
            j(g, isFound);
            delete g.filter.housingNumber;
            g.filter.structureNumber = f
        }
        if (!isFound.val) {
            j(g, isFound)
        }
    };
    this.refreshVisibility = function(b) {
        if (b == undefined) {
            var c = this.$formContainer.find('input[type="hidden"][name $= "[full]"]');
            var b = true;
            if (c.length) {
                b = (c.val() == "1")
            }
            if (!b && $("#street-text").length && $("#building-text").length) {
                if ($("#street-text input").val().length || $("#building-text input").val().length) {
                    b = true
                }
            }
        }
        if (b) {
            $(".field-full", this.$formContainer).show();
            $(".field-short", this.$formContainer).hide();
            $("#delivery-address-form", this.$formContainer).addClass("with-addr-text")
        } else {
            $(".field-full", this.$formContainer).hide();
            $(".field-short", this.$formContainer).show();
            $("#delivery-address-form", this.$formContainer).removeClass("with-addr-text")
        }
    };
    this.setAddressToForm = function(c) {
        fullForm = false;
        var b = false;
        if (c.country && $('#country-text option[value="' + c.country + '"]').length) {
            $("#country-text select").val(c.country).addClass("important-auto-data")
        }
        if (c.index) {
            $("#index-text input").val(c.index).addClass("important-auto-data")
        }
        if (c.region) {
            $("#region-text input").val(c.region).addClass("important-auto-data")
        }
        if (c.regionId) {
            $("#region-text input").autocomplete("option", "valueField").val(c.regionId)
        } else {
            b = true
        }
        if (c.city) {
            $("#city-text input").val(c.city).addClass("important-auto-data")
        }
        if (c.cityId) {
            $("#city-text input").autocomplete("option", "valueField").val(c.cityId)
        } else {
            b = true
        }
        if (c.street) {
            $("#street-text input").val(c.street).addClass("important-auto-data");
            fullForm = true
        }
        if (c.building) {
            $("#building-text input").val(c.building).addClass("important-auto-data");
            fullForm = true
        }
        if (c.house) {
            $("#house-text input").val(c.house).addClass("important-auto-data");
            fullForm = true
        }
        if (c.block) {
            $("#block-text input").val(c.block).addClass("important-auto-data");
            fullForm = true
        }
        if (c.floor) {
            $("#floor-text input").val(c.floor).addClass("important-auto-data");
            fullForm = true
        }
        if (c.flat) {
            $("#flat-text input").val(c.flat).addClass("important-auto-data");
            fullForm = true
        }
        if (c.text) {
            $("#address-text input, #address-text textarea").val(c.text).addClass("important-auto-data")
        }
        if (c.notes) {
            $("#address-notes input, #address-notes textarea").val(c.notes).addClass("important-auto-data");
            fullForm = true
        }
        this.refreshVisibility();
        var d = $("#country-text select");
        if (d.val() != "" && b) {
            d.change()
        } else {
            if (d.val() != "") {
                this.loadRegions(d.val())
            }
        }
    };
    this.autocompleteValueSpecified = function(b) {
        return b.autocomplete("option", "valueField").val() != ""
    };
    this.clearValueFromType = function(c, b) {
        if (b != "") {
            b += " ";
            if (c.slice(0, b.length) == b) {
                c = c.slice(b.length)
            } else {
                if (c.slice(-b.length) == b) {
                    c = c.slice(0, -b.length)
                }
            }
        }
        return c
    };
    this.getCity = function(c) {
        if (c !== false) {
            c = true
        }
        var b = this.$city.val();
        if (!c) {
            b = this.clearValueFromType(b, this.$formContainer.find('input[name $= "[cityType]"]').val())
        }
        return b
    };
    this.getStreet = function(c) {
        if (c !== false) {
            c = true
        }
        var b = this.$street.val();
        if (!c) {
            b = this.clearValueFromType(b, this.$formContainer.find('input[name $= "[streetType]"]').val())
        }
        return b
    }
}
$(".input-field-phone").live("change", function() {
    if (globalSettings.geohelper != undefined && !globalSettings.geohelper.getTimeByPhone) {
        return
    }
    var b = $(this);
    Time.removePhoneTime(b.siblings(".phone-info"), true);
    if ($(this).val().length < 10) {
        return
    }
    var a = Routing.generate("crm_dictionary_geohelper_phone_range");
    $.get(a, {
        filter: {
            phone: $(this).val()
        }
    }).success(function(e) {
        if (!e.success || e.result == undefined) {
            return
        }
        var m = undefined;
        var h = undefined;
        if (e.result.country != undefined && e.result.country.timezoneOffset != undefined) {
            m = e.result.country.timezoneOffset;
            h = e.result.country.name
        }
        if (e.result.region != undefined && e.result.region.timezoneOffset != undefined) {
            m = e.result.region.timezoneOffset;
            h = e.result.region.name
        }
        if (m == undefined) {
            return
        }
        var c = new Date();
        var j = c.getTime();
        var n = j + c.getTimezoneOffset() * 60000 + m * 1000;
        c = new Date(n);
        var l = c.getHours();
        if (l < 10) {
            l = "0" + l
        }
        var d = c.getMinutes();
        if (d < 10) {
            d = "0" + d
        }
        var g = m / 3600;
        var f = $(b.parents(".input-group").data("info-prototype"));
        f.find(".phone-info__time").text(l + ":" + d);
        Time.initPhoneTime(f.find(".phone-info__time"));
        f.find(".phone-info__heading").text(h);
        f.find(".phone-info__zone").text("UTC " + ((g > 0) ? "+" + g : g));
        if (e.result.providerName != undefined) {
            f.find(".phone-info__operator").text(e.result.providerName)
        }
        b.after(f)
    })
});
$(".phone-time").live("click", function(a) {
    $(this).find(".popup-comment-box").toggle();
    a.preventDefault();
    a.stopPropagation()
});

function isStringEmpty(a) {
    return !(a && a !== "")
}

function priceFormat(c, a, b) {
    if ("undefined" === typeof a) {
        a = 2
    }
    if (c % 1 === 0) {
        a = 0
    }
    if (b) {
        return numberFormat(c, a, ",")
    }
    var d = numberFormat(c, a, ",", "&nbsp;");
    switch (globalSettings.locale) {
        case "en_GB":
            return globalSettings.currencyHtml + "&nbsp;" + d;
        case "ru_RU":
        default:
            return d + "&nbsp;" + globalSettings.currencyHtml
    }
}

function numberFormat(d, b, l, c) {
    var f, e, h, g, a;
    if (isNaN(b = Math.abs(b))) {
        b = 2
    }
    if (l == undefined) {
        l = ","
    }
    if (c == undefined) {
        c = "."
    }
    f = parseInt(d = toFixed(+d || 0, b)) + "";
    if ((e = f.length) > 3) {
        e = e % 3
    } else {
        e = 0
    }
    a = (e ? f.substr(0, e) + c : "");
    h = f.substr(e).replace(/(\d{3})(?=\d)/g, "$1" + c);
    g = (b ? l + Math.abs(d - f).toFixed(b).replace(/-/, 0).slice(2) : "");
    return a + h + g
}

function toFixed(b, a) {
    var c = Math.pow(10, a);
    return Math.round(b * c) / c
}

function formatFloat(a) {
    return a.toString().replace(/\./, ",")
}

function numericDeclension(g, b, c) {
    if (typeof c == "undefined") {
        c = true
    }
    var f = b.split("/");
    if (f.length < 4) {
        return ""
    }
    b = f.shift();
    var h = g.toString();
    var e = h.length;
    var d = parseInt(h[e - 1]);
    var a = (e >= 2 ? h[e - 2] : "");
    var j = (c ? h + " " : "");
    if (h == "0" || a == "1" || (a != "1" && ((d >= 5 && d <= 9) || d == 0))) {
        return j + b + f[2]
    }
    if (a != "1" && d >= 2 && d <= 4) {
        return j + b + f[1]
    }
    if (a != "1" && d == 1) {
        return j + b + f[0]
    }
    return ""
}
$(document).ready(function() {
    var c = $(".message-indic, .double_widget_btn"),
        b = $(".popup-comment-box"),
        e = $(".pop-up-init"),
        d = $(".stat-box-popup-bg"),
        a = $(".status-tbl .have-ct");
    window.commentIconClick = function(g) {
        var f = $(".popup-comment-box", this);
        $(".popup-comment-box").not(f).hide();
        if (!f.is(":visible")) {
            if ($(this).parent().hasClass("list-status-comment") || $(this).parents().is("#order-products-table")) {
                f.css({
                    visibility: "hidden",
                    display: "block"
                });
                if ($(this).parent().hasClass("list-status-comment")) {
                    $table = $(".modern-table-container");
                    f.removeClass("down up");
                    if (f.offset().top + f.outerHeight() + 20 > $table.offset().top + $table.height() && $table.height() > f.outerHeight()) {
                        f.addClass("up")
                    } else {
                        f.addClass("down")
                    }
                    if (f.parent().offset().left - $table.offset().left - f.width() < 0) {
                        f.addClass("right")
                    } else {
                        f.removeClass("right")
                    }
                } else {
                    $table = $("body");
                    if (parseInt(f.parent().offset().left + f.width()) > $table.width()) {
                        f.addClass("right")
                    } else {
                        f.removeClass("right")
                    }
                }
                f.css({
                    visibility: "",
                    display: "none"
                })
            }
            f.fadeIn(250);
            f.find("textarea").focus();
            g.stopPropagation()
        } else {
            if ($(g.target).hasClass("message-indic") || !f.hasClass("static")) {
                f.fadeOut(250);
                g.stopPropagation()
            }
        }
    };
    c.click(commentIconClick);
    $("textarea", b).bind("keydown", function(f) {
        var g = false;
        if ("undefined" !== typeof f.metaKey) {
            g = f.metaKey
        }
        if (f.keyCode === 13 && (f.ctrlKey || g)) {
            $(this).parents(".popup-comment-box").find('.buttons [type="button"]').click();
            return false
        }
    });
    $(window).click(function(f) {
        if (!$(f.target).parents(".popup-comment-box.static").length) {
            $(".popup-comment-box").fadeOut(250)
        }
    });
    e.click(function() {
        $(this).next().fadeIn();
        $(this).next().next().show()
    });
    a.live("click", function() {
        $(this).toggleClass("show");
        if ($(this).hasClass("show")) {
            $(this).parents("tr").next().find("p").slideDown(250, function() {
                $(this).css("overflow", "")
            })
        } else {
            $(this).parents("tr").next().find("p").slideUp(250)
        }
    })
});

function UpdateDataInWidget(f) {
    var e = $(f).parents(".stat-box, .stat-box-popup");
    var b = $(".widget-interval.timeInterval .active a", e).attr("data-value");
    var a = $("li.active a.additional-stat-interval", e).attr("data-value");
    if (e.hasClass("stat-box")) {
        e.addClass("grayed").css("position", "relative")
    }
    var c = function() {
        dispatchAjaxError($(".stat-box-content", e))
    };
    var d = {
        interval: b,
        additional: a
    };
    if (e.hasClass("stat-box-popup")) {
        e.intaroPopup("updateContent", {
            url: e.attr("data-update-url"),
            data: d,
            dataType: "html",
            onSuccess: function() {
                $(".stat-box-content .multi-selects li:first-child", e).click()
            },
            onError: c
        })
    } else {
        $(".stat-box-content", e).html("").addClass("overpage o-bg o-w black-red-loader");
        currentAjax = $.ajax({
            url: e.attr("data-update-url"),
            data: d,
            dataType: "html",
            success: function(g) {
                if (e.hasClass("grayed")) {
                    e.removeClass("grayed").attr("style", "")
                }
                $(".stat-box-content", e).removeClass("overpage o-bg o-w black-red-loader");
                $(".stat-box-content", e).html(g).initJsControls();
                $(".stat-box-content .multi-selects li:first-child", e).click()
            },
            error: c
        })
    }
}

function dispatchAjaxError(a) {
    if (typeof ajaxBreak === "undefined" || !ajaxBreak) {
        a.html('<div class="no-data">' + _translate("message.error_on_load_try_later") + "</div>")
    } else {
        ajaxBreak = false
    }
}

function createMessagePopupBox(a, b) {
    $message = $("<div>").text(b).addClass("comment-text fs12 lh18 black");
    $box = $("<div>").addClass("popup-comment-box").append($message);
    a.after($box);
    $box.fadeIn();
    $(document).one("click", function() {
        $box.remove()
    })
}
sustainOnlineTimeoutHandler = null;

function sustainOnline() {
    $.ajax({
        url: userOptions.onlineUrl,
        type: "POST",
        success: function(b) {
            if ($(".save-box").hasClass("blocked")) {
                return
            }
            $(".save-box").find(".save-box__msg.save-box__msg-user").remove();
            var d = [];
            for (userId in b) {
                d.push(b[userId])
            }
            if (d.length > 0) {
                var a = _transchoice(d.length, "info.is_being_watchig") + " ";
                if (d.length <= 2) {
                    a += d.join(" " + _translate("info.and") + " ")
                } else {
                    a += d.slice(0, 2).join(", ") + ' <span title="' + d.slice(2).join(", ") + '">' + _transchoice(d.slice(2).length, "info.other_users") + "</span>"
                }
                var c = $("<div>").addClass("save-box__msg").addClass("save-box__msg-user").append($("<span>").addClass("text").html(a));
                $(".save-box .wrapper").append(c)
            }
        }
    })
}
if (typeof userOptions != "undefined" && userOptions.onlineTimeout > 15) {
    userOptions.onlineTimeoutDelta = (userOptions.onlineTimeout - 15) * 1000;
    sustainOnlineTimeoutHandler = setTimeout(sustainOnline, userOptions.onlineTimeoutDelta)
}
$(function() {
    $(document).ajaxSend(function() {
        clearTimeout(sustainOnlineTimeoutHandler)
    });
    $(document).ajaxComplete(function() {
        clearTimeout(sustainOnlineTimeoutHandler);
        sustainOnlineTimeoutHandler = setTimeout(sustainOnline, userOptions.onlineTimeoutDelta)
    })
});
String.prototype.hashCode = function() {
    var b = 0;
    if (this.length == 0) {
        return b
    }
    for (i = 0; i < this.length; i++) {
        var a = this.charCodeAt(i);
        b = ((b << 5) - b) + a;
        b = b & b
    }
    return b
};

function highlight(a) {
    a.removeClass("idle").addClass("active");
    setTimeout(function() {
        a.addClass("idle").removeClass("active")
    }, 1)
}

function parseFloatText(a) {
    return parseFloat(a.replace(/[^\d,.]/g, "").replace(/,/, "."))
}
window.autocompleteBreak($(".autocomplete-off:input"));

function truncate(a, b, c) {
    if (a.length <= b) {
        return a
    } else {
        if (typeof c == "undefined") {
            c = "..."
        }
        return a.slice(0, b) + c
    }
}

function getLength(a) {
    return Object.keys(a).length
}
new Clipboard("[data-clipboard-target]");

function callback_tinymce_init(b) {
    var a = b.contentDocument.body.innerHTML;
    if (/<table/.test(a)) {
        b.contentDocument.body.innerHTML = a.replace(/></g, ">\n<")
    }
    b.contentDocument.addEventListener("DOMNodeInserted", function(d) {
        if (d.target.innerHTML) {
            var c = d.target.innerHTML.replace(/></g, ">\n<");
            d.target.innerHTML = c
        }
    }, false)
}

function filter_vat_rates_by_country(b, a) {
    if (!a) {
        a = ".vat-rate-field"
    }
    a = $(a);
    b = b ? $.makeArray(b) : [];
    b = $.map(b, function(c) {
        return (c + "").toLowerCase()
    });
    a.find("option").each(function() {
        var d = $(this),
            f = d.data("countries"),
            e = false;
        if (!f) {
            return
        }
        $.each(b, function(c, g) {
            e = (f + "").indexOf(g) >= 0;
            if (e) {
                return false
            }
        });
        if (e) {
            d.show()
        } else {
            d.hide()
        }
    });
    a.trigger("chosen:updated")
}(function() {
    $(".js-show-target").each(function() {
        var b = $(this),
            a = $('[name="' + b.attr("name") + '"]');
        a.live("change", function() {
            var c = $("#" + b.data("target"));
            if (b.prop("checked")) {
                c.removeClass("hide")
            } else {
                c.addClass("hide")
            }
        })
    })
})();
$(".style-tumbler").live("click", function() {
    var b = $(this);
    var a = b.parent();
    if (!b.hasClass("tumb-right")) {
        a.children(".tumbler-item").eq(1).addClass("active").siblings().removeClass("active")
    } else {
        a.children(".tumbler-item").eq(0).addClass("active").siblings().removeClass("active")
    }
    a.children("input").click();
    b.toggleClass("tumb-right")
});
$(".style-tumbler-wrap .tumbler-item").live("click", function(d) {
    d.preventDefault();
    var c = $(this);
    var a = c.parent();
    var b = a.children(".style-tumbler");
    if (!c.hasClass("active")) {
        c.addClass("active").siblings(".tumbler-item").removeClass("active");
        b.toggleClass("tumb-right");
        a.children("input").click()
    }
});
$(function() {
    $("form").live("submit", function(n) {
        var m = $(this);
        if (m.find(".save-box").length <= 0) {
            return
        }
        if ($(this).data("submit")) {
            n.preventDefault();
            return
        }
        var f = $(document.activeElement);
        if (!f.hasClass("submit-wo-disable")) {
            $(this).data("submit", true);
            m.find(".save-box input[type=submit], .save-box button, .save-box a.del-btn").addClass("disabled")
        }
    });
    $(".m-filter .filter-up").live("click", function() {
        var n = $(this).parents(".m-filter");
        var o = n.find(".filter-content");
        var q = n.find(".filter-control");
        var f = $(".filter-changed", n);
        var m = "121px";
        var p = 300;
        if (o.is(":visible")) {
            $(this).html(_translate("filter.show"));
            n.addClass("close");
            o.slideUp(p);
            if (f.length) {
                f.slideDown(p, function() {
                    $(this).css("overflow", "")
                })
            }
            $.cookie("is" + sectionUniqueName + "FilterClosed", "true", {
                expires: 30
            })
        } else {
            $(this).html(_translate("filter.hide"));
            n.removeClass("close");
            o.slideDown(p, function() {
                $(this).css("overflow", "")
            });
            if (f.length) {
                f.slideUp(p)
            }
            $.cookie("is" + sectionUniqueName + "FilterClosed", "false", {
                expires: 30
            })
        }
    });
    if (typeof sectionUniqueName != "undefined" && $.cookie("is" + sectionUniqueName + "FilterClosed") == "true") {
        $.cookie("is" + sectionUniqueName + "FilterClosed", "true", {
            expires: 30
        })
    }
    if (typeof sectionUniqueName != "undefined") {
        if ($.cookie("is" + sectionUniqueName + "FilterClosed") == "true") {
            $(".filter-content").hide()
        } else {}
    }
    $(".m-filter .filter-cancel").live("click", function() {
        removeQueryFromLocalStorage(location.pathname);
        window.location = location.pathname
    });
    tdClickHandler = function(n) {
        var p = getSelection().toString();
        var f = n.target || n.srcElement;
        if ($(this).hasClass("no-click") || p) {
            return
        }
        if ($(this).parents().hasClass("ajax-tr")) {
            $(this).parent().find("a:first-child").click();
            return
        }
        if ($(f).parents(".no-tr-link").length || $(f).is(".no-tr-link") || $(this).parent().hasClass("no-tr-link")) {
            return
        }
        var m = $(this).parent();
        if (m.data("url")) {
            location.href = m.data("url");
            return
        }
        var o = m.find("a:first");
        if (o.length) {
            location.href = o.attr("href")
        }
    };
    $(".m-box table.list:not(.no-handler) tbody").delegate("td", "click", tdClickHandler);
    $(".m-box table.list:not(.no-handler) tbody").delegate("tr a:not(.has-listener)", "click", function(f) {
        f.stopPropagation()
    });
    $(".m-box div.limit a").live("click", function(f) {
        $(".m-box div.limit a.active").removeClass("active");
        $(this).addClass("active")
    });
    $(".m-pagination .pagination ul li").live("click", function() {
        if (!$(this).parents().hasClass("ajax-pagination")) {
            var f = $(this).find("a:first-child").attr("href");
            if (f.length > 0) {
                location = f
            }
        } else {
            $(this).find("a:first-child").click()
        }
    });
    $("#s-tabs a, .s-tabs a, #m-tabs a").click(function(f) {
        jsTabs($(this), f)
    });
    $("#s-tabs-system a, .s-tabs-system a").live("click", function(p) {
        var n = $(this);
        var m = n.closest("li");
        if (m.hasClass("disabled")) {
            p.preventDefault();
            return false
        }
        var f = $.trim(n.attr("href"));
        var o = false;
        var q = false;
        if (typeof n.attr("id") === "string") {
            q = n.attr("id").replace("tab-", "");
            o = $("#section-" + q)
        }
        if (!o) {
            return true
        }
        p.preventDefault();
        o.show().trigger("tabs-system:show").siblings(".tab-section").hide().trigger("tabs-system:hide");
        m.addClass("active").siblings().removeClass("active");
        if (f && f !== "#" && !o.data("loaded")) {
            o.trigger("tabs-system:loading");
            o.addClass("overlay overlay-white o-bg black-red-loader");
            $.get(f, function(r) {
                o.html(r).removeClass("overlay overlay-white black-red-loader");
                o.data("loaded", true);
                o.trigger("tabs-system:loaded")
            })
        }
        document.location.hash = "t-" + q
    });
    $(".box-tabs > li > a").click(function() {
        var f = $.trim($(this).data("value"));
        var m = $(this).parents(".box-tabs-container");
        if (!m.length) {
            return false
        }
        m = m.find(".box-tabs-sections");
        if (!m.length) {
            return false
        }
        m.children().hide();
        m.find("#box-tabs-section-" + f).show();
        $(this).parent().parent().children().removeClass("active");
        $(this).parent().addClass("active");
        if ($(this).data("tab-area")) {
            $.cookie($(this).data("tab-area"), $(this).data("value"), {
                expires: 30,
                path: "/"
            })
        }
        return false
    });
    var e = $(".s-tabs-system"),
        h = document.location.hash.replace(/^#/, "").split("#");
    if (h.length) {
        h.forEach(function(f) {
            var m = "tab-" + f.replace(/^t-/, "");
            $("#s-tabs li a#" + m).click();
            $(".s-tabs li a#" + m).click();
            $("#s-tabs-system li a#" + m).click();
            $("#m-tabs li a#" + m).click();
            e.each(function() {
                var p = $(this),
                    o = p.find("li:not(.disabled)");
                if (p.hasClass("s-tabs-drop")) {
                    var n = o.find("#" + m).length ? o.find("#" + m) : o.first().find("a"),
                        q = n.closest("li");
                    if (q.hasClass("invisible")) {
                        q.removeClass("invisible");
                        p.siblings(".dropdown").find("ul li").eq(q.index()).find("a").click()
                    } else {
                        n.click()
                    }
                } else {
                    o.find("#" + m).length ? o.find("#" + m).click() : o.first().find("a").click()
                }
            })
        });
        if (h.length < $(".s-tabs, #s-tabs").length) {
            $("#s-tabs:visible, .s-tabs:visible").each(function() {
                var f = $(this);
                if (f.find("li.active a").length) {
                    f.find("li.active a").click()
                } else {
                    f.find("li:first-child a").click()
                }
            })
        }
    } else {
        var b = false;
        if ($("#s-tabs li.active a, .s-tabs li.active a").length || $("#s-tabs-system li.active a").length || e.find("li.active").find("a").length) {
            $("#s-tabs li.active a").click();
            $(".s-tabs li.active a").click();
            $("#s-tabs-system li.active a").click();
            e.each(function() {
                var f = $(this),
                    m = f.find("li.active");
                if (f.hasClass("s-tabs-drop")) {
                    if (m.hasClass("invisible")) {
                        m.removeClass("invisible");
                        f.siblings(".dropdown").find("ul li").eq(m.index()).find("a").click()
                    } else {
                        m.find("a").click()
                    }
                } else {
                    m.find("a").click()
                }
                if (!m.length) {
                    f.find("li:first-child a").click()
                }
            });
            b = true
        }
        var j = "";
        if (!b && $("#main .msg-error").length) {
            j = $("form.crud .controls .msg-error:first");
            if (j.length) {
                var i = j.parents("fieldset");
                if (i.length) {
                    var c = i.attr("id").replace("section-", "");
                    $("#s-tabs li a#tab-" + c).click();
                    b = true
                }
            }
        }
        if (!b) {
            $("#s-tabs li:first a").click();
            $("#s-tabs-system li:first a").click();
            e.find("li:first-child a").click()
        }
        b = false;
        if ($("#m-tabs li.active a").length || $("#m-tabs-system li.active a").length) {
            $("#m-tabs li.active a").click();
            $("#m-tabs-system li.active a").click();
            b = true
        }
        if (!b && $("#main .msg-error").length) {
            j = $("form.crud .controls.msg-error:first");
            if (j.length) {
                var i = j.parents("fieldset");
                if (i.length) {
                    var c = i.attr("id").replace("section-", "");
                    $("#m-tabs li a#tab-" + c).click();
                    b = true
                }
            }
        }
        if (!b) {
            $("#m-tabs li:first a").click();
            $("#m-tabs-system li:first a").click()
        }
    }
    $("label.hint span").hover(function() {
        var f = $(this).find("i");
        if (!f.is(":visible")) {
            f.fadeIn()
        }
    }, function() {
        $(this).find("i").fadeOut()
    });
    initAceEditor($("textarea.ace-editor"));
    $('.m-box select[id$="catalogType"]').change(function() {
        var f = $(this).val();
        $(".catalog-parameters").each(function() {
            var m = $(this);
            if (m.hasClass("catalog-type-" + f)) {
                m.show()
            } else {
                m.hide()
            }
        })
    }).change();
    $(".status-matrix").delegate("th.sg", "click", function() {
        var n = $(this),
            f, m;
        if (n.hasClass("row")) {
            f = n.parent().index();
            m = n.closest(".fht-table-wrapper").find(".fht-fixed-body .fht-tbody .fht-table tbody tr").eq(f).find("input[type=checkbox]");
            m.prop("checked", !m.filter(":checked").length)
        } else {
            f = n.index();
            m = n.closest(".fht-table-wrapper").find(".fht-fixed-body .fht-tbody .fht-table tbody tr td:nth-child(" + (f + 2) + ")").find("input[type=checkbox]");
            m.prop("checked", !m.filter(":checked").length)
        }
    });
    $("input.disappearing ").bind("change focusout", function() {
        k(this)
    });
    $("input.disappearing ").each(function() {
        k(this)
    });

    function k(f) {
        if ($("input[type=checkbox].manage-disappear").is(":checked")) {
            if (/^[0-9UA\-]+$/.test($(f).val())) {
                $(f).removeClass("disappeared")
            } else {
                $(f).attr("placeholder", _translate("placeholder.tracking_disabled"));
                $(f).addClass("disappeared")
            }
        } else {
            $(f).attr("placeholder", _translate("placeholder.tracking_disabled"));
            $(f).addClass("disappeared")
        }
    }
    $("input[type=text].disappearing ").focus(function() {
        if (!/^[0-9]+$/.test($(this).val())) {
            $(this).attr("placeholder", "")
        }
        $(this).removeClass("disappeared")
    });
    $("input[type=checkbox].manage-disappear").click(function() {
        if ($(this).is(":checked")) {
            $("input[type=text].disappearing").each(function() {
                if (/^[0-9]+$/.test($(this).val())) {
                    $(this).removeClass("disappeared")
                }
            })
        } else {
            $("input[type=text].disappearing").addClass("disappeared")
        }
    });
    $("form.default-form-filter").submit(function(q) {
        q.preventDefault();
        var o = getFilterData(this);
        var f = $("<form>").css("display", "none").appendTo($("body"));
        f.attr("method", "get").attr("action", $(this).attr("action"));
        for (var n in o) {
            if (n.substr(-2) == "[]") {
                for (var m in o[n]) {
                    $('<input type="hidden">').appendTo(f).attr("name", n).val(o[n][m])
                }
            } else {
                $('<input type="hidden">').appendTo(f).attr("name", n).val(o[n])
            }
        }
        var r = "";
        for (var p in o) {
            if (p.substr(-2) == "[]") {
                for (var m in o[p]) {
                    r += encodeURIComponent(p) + "=" + encodeURIComponent(o[p][m]) + "&"
                }
            } else {
                r += encodeURIComponent(p) + "=" + encodeURIComponent(o[p]) + "&"
            }
        }
        r = r.replace(/[&]$/, "");
        f.submit()
    });
    var l = 0;
    $("img.intaro-image-preview").live("mouseenter", function() {
        if ($(this).data("source")) {
            if ($(this).data("preview-id")) {
                $("#" + $(this).data("preview-id")).css({
                    top: $(this).offset().top + $(this).height(),
                    left: $(this).offset().left
                }).show()
            } else {
                l++;
                $(this).data("preview-id", "image-preview-" + l);
                $('<div class="image-preview" id="image-preview-' + l + '"><img src="' + $(this).data("source") + '"></div>').appendTo($("body")).css({
                    top: $(this).offset().top + $(this).height(),
                    left: $(this).offset().left
                })
            }
        }
    }).live("mouseleave", function() {
        if ($(this).data("preview-id")) {
            $("#" + $(this).data("preview-id")).hide()
        }
    });
    $(".m-filter .filter-control .apply").click(function() {
        $(".default-form-filter").submit()
    });
    $(".m-filter .temp-bk .temp[data-url]").click(function() {
        var f = $(this).data("url");
        var m = f;
        location.href = f
    });
    if ($(".filter-save .msg-error").length) {
        $(".filter-save").addClass("open").find(".hide-lt-bk").show()
    }
    if ($(".m-filter").length) {
        saveQueryToLocalStorage(location.search.substr(1))
    }
    if (isLocalStorageAvailable()) {
        var a = window.localStorage.getItem("lastListFilters");
        a = JSON.parse(a);
        if (!a) {
            a = []
        }
        for (var d in a) {
            if (a[d].queryString.length) {
                var g = $('a[href="' + a[d].pathname + '"]');
                g.attr("href", g.attr("href") + "?" + a[d].queryString);
                var g = $('a[data-return-href="' + a[d].pathname + '"]');
                g.attr("data-return-href", g.attr("data-return-href") + "?" + a[d].queryString)
            }
        }
    }
    $("select#intaro_crmbundle_ordertype_deliveryType").change(function() {
        var f = $("select#intaro_crmbundle_ordertype_deliveryService");
        getDeliveryServices($(this).val(), $("select#intaro_crmbundle_ordertype_deliveryService option:selected").val());
        if (f.is(":visible")) {
            f.attr("required", "required")
        } else {
            f.removeAttr("required")
        }
    });
    $(".demo-block a").click(function() {
        if (confirm(_translate("confirm.delete_demo_data"))) {
            var f = $('<form method="POST" action="' + this.href + '" />');
            $("body").append(f);
            f[0].submit()
        }
        return false
    });
    $("a.auth-token").live("click", function() {
        var f = $('<form method="POST" action="' + this.href + '" />');
        f.append('<input type="hidden" name="auth_token" value="' + $(this).data("auth-token") + '" />');
        $("body").append(f);
        f[0].submit();
        return false
    });
    $("code.placeholder").live("click", function() {
        var m = $(this);
        var f = m.attr("rel");
        if (!f.length) {
            return false
        }
        $(".placeholder-" + f).each(function() {
            var o = $(this);
            if (m.hasClass("placeholder-append")) {
                o.val(o.val() + m.html())
            } else {
                o.val(m.html())
            }
            if (o.data("aceEditor")) {
                var n = o.data("aceEditor").session;
                n.insert({
                    row: n.getLength(),
                    column: 0
                }, "\n" + $.trim(m.html()))
            }
        });
        return false
    });
    $("[data-site-mapping-dependence]").live("change", function() {
        var r = $(this).val();
        var f = $(this).data("site-mapping-dependence").split(",");
        for (var n in f) {
            var q = $(f[n]);
            if (q.length) {
                if (r.length) {
                    var m = JSON.parse(q.attr("data-site"));
                    $("option", q).attr("disabled", false).show();
                    for (var o in m) {
                        var p = m[o];
                        if (Array.isArray(p)) {
                            if (p.indexOf(parseInt(r)) == -1) {
                                $('option[value="' + o + '"]', q).attr("disabled", true).hide()
                            }
                        } else {
                            if (r != p) {
                                $('option[value="' + o + '"]', q).attr("disabled", true).hide()
                            }
                        }
                    }
                } else {
                    $("option", q).attr("disabled", false).show()
                }
                q.initJsControls()
            }
            q.trigger("chosen:updated")
        }
    }).change();
    $(".mm-ss-mask").mask("99:99")
});

function getDeliveryServices(b, a) {
    var c = $("select#intaro_crmbundle_ordertype_deliveryService");
    if (deliveryServices === undefined) {
        return false
    }
    if (deliveryServices[b] === undefined) {
        c.parent().hide();
        $("select#intaro_crmbundle_ordertype_deliveryService option:gt(0)").remove();
        return false
    }
    $("select#intaro_crmbundle_ordertype_deliveryService option:gt(0)").remove();
    $.each(deliveryServices[b], function(d, e) {
        if (d == a) {
            var f = $("<option></option>").attr({
                value: d,
                selected: "selected"
            }).text(e)
        } else {
            var f = $("<option></option>").attr("value", d).text(e)
        }
        c.append(f)
    });
    if (c.is(":visible")) {
        c.attr("required", "required")
    } else {
        c.removeAttr("required")
    }
    c.trigger("chosen:updated");
    c.parent().show()
}

function getFilterData(a) {
    var k = $(a);
    var b = {};
    var f = k.serializeArray();
    for (var d in f) {
        var c = f[d];
        if (c.value != "") {
            var g = $('[name="' + c.name + '"]', k);
            if (g.length) {
                var e = g.parents(".slider-price");
                if (e.length) {
                    if (g.is($("input:first", e))) {
                        var h = parseInt(g.val());
                        if (h > 0) {
                            b[c.name] = h
                        }
                    } else {
                        if (g.is($("input:last", e))) {
                            var h = parseInt(g.val());
                            var j = parseInt($(".sp-right-param", e).text());
                            if (h < j && h > 0) {
                                b[c.name] = h
                            }
                        }
                    }
                } else {
                    if (c.name.substr(-2) == "[]") {
                        if (typeof b[c.name] === "undefined") {
                            b[c.name] = []
                        }
                        b[c.name].push(c.value)
                    } else {
                        if (g.attr("multiple")) {
                            if (b[c.name] == undefined) {
                                b[c.name] = []
                            }
                            b[c.name].push(c.value)
                        } else {
                            b[c.name] = c.value
                        }
                    }
                }
            }
        }
    }
    return b
}

function saveQueryToLocalStorage(c) {
    if (isLocalStorageAvailable()) {
        var a = window.localStorage.getItem("lastListFilters");
        a = JSON.parse(a);
        if (!a) {
            a = []
        }
        isExist = false;
        for (var b in a) {
            if (a[b].pathname === location.pathname) {
                a[b].queryString = c;
                isExist = true
            }
        }
        if (!isExist) {
            a.push({
                pathname: location.pathname,
                queryString: c
            })
        }
        window.localStorage.setItem("lastListFilters", JSON.stringify(a))
    }
}

function removeQueryFromLocalStorage(c) {
    if (c && isLocalStorageAvailable()) {
        var a = window.localStorage.getItem("lastListFilters");
        a = JSON.parse(a);
        if (!a) {
            a = []
        }
        for (var b in a) {
            if (a[b].pathname === c) {
                a.pop(b)
            }
        }
        window.localStorage.setItem("lastListFilters", JSON.stringify(a))
    }
}

function popupOpenByUrl(c, d, a) {
    var b = d ? $("#" + d) : $("#independent-popup-bk");
    if (!b.length) {
        $.error("empty popup " + b)
    }
    b.intaroPopup({
        url: c,
        onSuccess: function() {
            initMessageForm(b);
            if (typeof a == "function") {
                a.call(b)
            }
        },
        onError: function() {
            alert(_translate("alert.request_error_try_later"))
        }
    })
}

function onPopupOpen() {
    popupOpenByUrl($(this).data("url") || $(this).attr("href"), $(this).data("popup"));
    return false
}
initAceEditor = function(a) {
    a.each(function() {
        var b = $(this),
            d = b.attr("id") + "-ace-editor",
            f = $("#" + d);
        f = f.length ? f : b.prev(".small-text-editor");
        if (!f.length && b.parent().prev(".small-text-editor").length) {
            f = b.parent().prev(".small-text-editor");
            f.attr("id", d)
        }
        if (!f.length) {
            f = $("<div/>", {
                id: d,
                "class": "tiny-text-editor"
            }).insertBefore(b)
        }
        var c = ace.edit(d),
            e = $("#ace-editor-value-length");
        b.hide();
        f.show();
        c.setTheme("ace/theme/chrome");
        c.session.setMode(f.data("mode") || "ace/mode/twig");
        if (b.hasClass("wrap")) {
            c.session.setUseWrapMode(true)
        }
        if (b.hasClass("disable-worker")) {
            c.session.setUseWorker(false)
        }
        c.session.setValue(b.val());
        c.session.on("change", function() {
            b.val(c.session.getValue());
            if (e.length) {
                e.html(c.session.getValue().length + " " + _transchoice(c.session.getValue().length, "measure.symbol.full"))
            }
            return false
        })();
        b.data("aceEditor", c)
    })
};

function jsTabItems(a, b) {
    a.parent().parent().find("li").removeClass("active");
    a.parent().addClass("active");
    $.cookie("s-tabs-" + a.parent().parent().attr("id"), a.parent().data("update-url"), {
        expires: 30,
        path: "/"
    })
}

function jsTabs(f, h) {
    var a = $.trim(f.attr("href")),
        d = a.split("#");
    if (!f.attr("id")) {
        return
    }
    if (a.length == 0 || a == "#" || a == "javascript:void(0);" || d.length > 1 && d[0] == document.location.pathname) {
        h.preventDefault();
        var i = f.attr("id").replace("tab-", ""),
            g = "t-",
            c = /^s-/.test(i),
            b = document.location.hash.replace(/^#/, "").split("#").map(function(e) {
                return e.replace(/^t-/, "")
            });
        if (b.length > 1) {
            if (c) {
                b[1] = i
            } else {
                b[0] = i
            }
            b.forEach(function(j, e) {
                if (!e) {
                    g += j
                } else {
                    g += "#t-" + j
                }
            })
        } else {
            if (c) {
                g = document.location.hash + "#" + g + i
            } else {
                g += i
            }
        }
        $("#section-" + i).show().siblings("fieldset").hide();
        document.location.hash = g;
        jsTabItems(f, h);
        return false
    }
}(function() {
    var b = $(".sub-nav-top .m-tabs"),
        c = b.siblings(".dropdown");
    b.addClass("s-tabs-drop");
    if (!c.length) {
        c = $('<div class="dropdown hide"><a href="#" class="grid__tab-more">ֵשו</a><div class="ul-scroll-dropdown-wrapper"><ul/></div></div>');
        var a = "";
        b.find("li > a").each(function() {
            var e = $(this),
                d = e.attr("href"),
                f = e.text();
            a += '<li><a href="' + d + '">' + f + "</a></li>"
        });
        c.find("ul").append(a);
        b.after(c)
    }
})();
(function() {
    var a = $(".s-tabs-drop");
    if (a.length) {
        a.each(function() {
            var d = $(this),
                b = d.find("li"),
                g = d.siblings(".dropdown"),
                f = g.find("ul li"),
                e = f.find("a");
            c();
            e.unbind("click").bind("click", function(i) {
                if (!g.closest(".sub-nav-top").length) {
                    i.preventDefault()
                }
                var h = $(this),
                    j = "tab-" + h.attr("data-tab");
                h.closest("li").addClass("hide");
                b.find("#" + j).trigger("click").closest("li").removeClass("invisible");
                c()
            });
            $(window).resize(c);

            function c() {
                var j = d.parent().width(),
                    h = 0,
                    i = b.filter(".active").outerWidth();
                d.siblings().each(function() {
                    h += $(this).outerWidth()
                });
                b.each(function() {
                    i += $(this).outerWidth()
                });
                if (j - i < 0) {
                    i = b.filter(".active").outerWidth();
                    b.not(".active").each(function() {
                        var k = $(this);
                        i += k.outerWidth();
                        if (j - i < h + 10) {
                            k.addClass("invisible");
                            f.eq(k.index()).removeClass("hide")
                        } else {
                            k.removeClass("invisible");
                            f.eq(k.index()).addClass("hide")
                        }
                    });
                    f.eq(b.filter(".active").index()).addClass("hide");
                    if (f.filter(".hide").length == f.length) {
                        g.addClass("hide")
                    } else {
                        g.removeClass("hide")
                    }
                } else {
                    b.removeClass("invisible");
                    g.addClass("hide")
                }
            }
        })
    }
})();
(function() {
    $(window).resize(a);
    a();

    function a() {
        if (window.innerWidth <= 1353) {
            $(".js-wide-tabs").each(function() {
                var b = $(this),
                    c = b.width(),
                    e = b.children(),
                    d = 0;
                e.each(function() {
                    d += $(this).outerWidth()
                });
                if (d > c) {
                    e.css({
                        width: c / e.length
                    })
                } else {
                    e.each(function() {
                        var f = $(this);
                        f.css({
                            width: f.outerWidth() * c / d
                        })
                    })
                }
            })
        } else {
            $(".js-wide-tabs").children().removeAttr("style")
        }
    }
})();
(function() {
    $(".info-box__close").live("click", function(f) {
        f.preventDefault();
        var d = $(this),
            c = d.closest(".info-box"),
            a = d.closest(".grid__row"),
            b = 300;
        c.fadeOut(b, function() {
            a.addClass("no-info")
        })
    })
})();
(function() {
    var a = $(".rule__segments");
    if (a.length) {
        a.each(function() {
            var d = $(this),
                c = d.find(".rule__segment"),
                b = d.siblings(".rule__segments-more"),
                f = b.find(".rule__segments-counter");
            e();
            $(window).resize(e);

            function e() {
                var h = d.parent().width(),
                    g = 0,
                    j = c.outerWidth(),
                    i = b.width();
                d.siblings().each(function() {
                    g += $(this).outerWidth()
                });
                c.each(function() {
                    j += $(this).outerWidth()
                });
                if (h - j < 0) {
                    j = c.outerWidth();
                    c.each(function() {
                        var k = $(this);
                        j += k.outerWidth();
                        var l = i ? 10 : 60;
                        if (h - j < g + l) {
                            k.addClass("invisible");
                            b.eq(k.index()).removeClass("hide")
                        } else {
                            k.removeClass("invisible");
                            b.eq(k.index()).addClass("hide")
                        }
                    });
                    f.text(c.filter(".invisible").length);
                    if (!c.filter(".invisible").length) {
                        b.addClass("hide")
                    } else {
                        b.removeClass("hide")
                    }
                } else {
                    c.removeClass("invisible");
                    b.addClass("hide")
                }
            }
        })
    }
})();
$(document).ready(function() {
    $('input[type="time"]').each(function() {
        if ($(this).prop("type") !== "time") {
            $(this).mask("99:99")
        }
    });
    var y = $(".export-modal");
    $(".filter-content .filter-group").each(function() {
        var D = $(this).parents(".filter-content");
        var C = false;
        if (!D.is(":visible")) {
            $(this).parents(".filter-content").show();
            C = true
        }
        if (C) {
            $(this).parents(".filter-content").hide()
        }
    });
    $(".temp-bk .temp").click(function() {
        $(".temp").removeClass("active");
        $(this).addClass("active")
    });
    $(".inner-lt").click(function() {
        if ($(this).parent().hasClass("open")) {
            $(this).parent().removeClass("open");
            $(this).next().hide();
            $(this).closest(".temp-bk").removeClass("over-vis")
        } else {
            $(this).parent().addClass("open");
            $(this).next().show();
            if ($(this).closest(".temp-bk").css("overflow") == "hidden") {
                $(this).closest(".temp-bk").addClass("over-vis")
            }
        }
    });
    var p = $(".hide-lt-bk .cancel");
    p.click(function(C) {
        $(this).parents(".lt-change").removeClass("open");
        $(this).parents(".hide-lt-bk").hide()
    });
    $(window).click(function(C) {
        if (!$(C.target).parents(".lt-change").length && !$(C.target).hasClass("lt-change")) {
            $(".lt-change").removeClass("open");
            $(".lt-change .hide-lt-bk").hide();
            $(".temp-bk.over-vis").removeClass("over-vis")
        }
        if (!$(C.target).parents(".filter-save").length && !$(C.target).hasClass("filter-save")) {
            $(".filter-save").removeClass("open");
            $(".filter-save .hide-lt-bk").hide()
        }
    });
    $(".inner-save").click(function() {
        $(this).parent().addClass("open");
        $(this).next().show()
    });
    var p = $(".hide-lt-bk .cancel");
    p.click(function(C) {
        $(this).parents(".filter-save").removeClass("open");
        $(this).parent(".hide-lt-bk").hide()
    });
    var i = $(".temp-bk"),
        z, q;
    $(window).bind("load resize", function() {
        if (!q) {
            q = setTimeout(function() {
                z = 0;
                i.children(".temp").each(function() {
                    z = z + $(this).outerWidth(true)
                });
                if (i.innerWidth() < z) {
                    i.addClass("collapsed")
                } else {
                    i.removeClass("collapsed").height("")
                }
                if (typeof sectionUniqueName != "undefined" && $.cookie("is" + sectionUniqueName + "TempFilterClosed") == "false") {
                    c()
                }
                q = null
            }, 50)
        }
    });

    function c() {
        i.addClass("open");
        $.cookie("is" + sectionUniqueName + "TempFilterClosed", "false", {
            expires: 30
        })
    }
    $(".temp-bk__btn-toggle", i).live("click", function() {
        if (!i.hasClass("open")) {
            c()
        } else {
            i.removeClass("open");
            $.cookie("is" + sectionUniqueName + "TempFilterClosed", "true", {
                expires: 30
            })
        }
    });
    var x = $(".m-box").height(),
        k = $(".status-bar"),
        u = k.width(),
        w = $(".modern-table-container"),
        o = $(".closed", k),
        b = $(".opened", k),
        j = b.height(),
        f = $(".roll-up"),
        a = $(".status-bar-list ul li");
    statBarW = "140px";
    k.live("click", function(D) {
        D.stopPropagation();
        var C = $(this);
        if (C.hasClass("close")) {
            C.removeClass("close");
            if (typeof sectionUniqueName != "undefined") {
                $.cookie("is" + sectionUniqueName + "StatusBarClosed", "false")
            }
        }
    });
    f.live("click", function(C) {
        C.stopPropagation();
        if (k.hasClass("close")) {
            return
        }
        k.addClass("close");
        if (typeof sectionUniqueName != "undefined") {
            $.cookie("is" + sectionUniqueName + "StatusBarClosed", "true")
        }
    });
    if (typeof sectionUniqueName != "undefined" && $.cookie("is" + sectionUniqueName + "StatusBarClosed") == "false") {
        k.removeClass("close")
    }
    $(document).delegate(".m-filter .flags-in-filter div[data-input-id]", "click", function() {
        var D = $(this).data("input-id");
        D = "filter_" + D;
        var C = $("#" + D);
        $(this).toggleClass("os-select");
        if ($(this).is(".os-select")) {
            C.val(1)
        } else {
            C.val("")
        }
    });

    function s(D) {
        var J = D.find(".columns");
        var G = J.find(".td:first-child");
        var E = J.find(".enabled.sortable"),
            F = J.find(".disabled"),
            K, L = E.children("li").length;
        if (F.height() == 0) {
            K = L * 40;
            if (K > 500) {
                K = 500
            }
        } else {
            K = Math.max(E.height(), F.height())
        }
        K = K < 160 && L == 0 ? 160 : K;
        var M = Math.floor(K / E.children("li").last().outerHeight()),
            H = Math.ceil(E.children("li").length / M),
            C = E.children("li").first().outerWidth(),
            I = H * C;
        if (I == 0 && L == 0) {
            G.width(190)
        } else {
            G.width(I)
        }
        if (!E.has(".parameters-group")) {
            E.height(K);
            F.height(K)
        }
    }

    function l(F) {
        var C = F.find(".enabled.sortable");
        var D = F.find(".disabled");
        D.find("input:checked").each(function() {
            C.append($(this).parent("li"))
        });
        var E = {};
        D.find(".group-field").each(function() {
            E[$(this).data("group")] = $(this).next("ul")
        });
        C.find("input:checkbox:not(:checked)").each(function() {
            var G = $(this).parent("li");
            B(E[G.data("group")], G)
        });
        e(D);
        d(C);
        $('.export-modal input[value="delivery_address"], .export-modal input[value="address"]').each(function() {
            var G = $("#listexport_explodeAddress");
            if ($(this).is(":checked")) {
                G.parents("li").show()
            } else {
                G.parents("li").hide()
            }
        })
    }
    $(".parameters").click(function(D) {
        var C = $(D.target),
            G = $(this),
            F;
        if (C.is(".parameters")) {
            C.toggleClass("active");
            if (C.hasClass("with-modal")) {
                F = G.find(".parameters-popup-content");
                F.intaroPopup({
                    wrapperClass: "parameters-popup"
                });
                setTimeout(function() {
                    s(G);
                    l(G);
                    s(G)
                }, 50);
                $('.parameters.active .parameters-popup input[type="checkbox"]').click(function() {
                    setTimeout(function() {
                        s(G)
                    }, 50)
                });
                F.find(".close").click(function() {
                    setTimeout(function() {
                        $(".parameters.active").removeClass("active")
                    }, 400)
                })
            } else {
                F = $(this).find(".parameters-popup:visible");
                if (F.length) {
                    var E = F.offset();
                    if (E.top + 26 + F.children(".parameters-popup-content").height() > $(document).height()) {
                        F.css("height", $(document).height() - E.top - 26);
                        F.addClass("wide")
                    } else {
                        F.removeClass("wide")
                    }
                }
            }
        }
    });
    $(window).click(function(E) {
        var D = $(".parameters.active");
        var C = $(E.target);
        if (D.length && !C.is(".parameters") && !C.parents(".parameters").length) {
            D.removeClass("active")
        }
    });
    $("ul.sortable").sortable({
        placeholder: "ui-state-highlight"
    });
    $(".draggable-rows.enabled").sortable({
        start: function(C, D) {
            pgRowNext = D.item.nextAll(".p-group-row").not(".ui-sortable-placeholder").length
        },
        stop: function(D, E) {
            var C = pgRowNext - E.item.nextAll(".p-group-row").not(".ui-sortable-placeholder").length;
            if (C !== 0) {
                $.ajax({
                    type: "post",
                    url: E.item.data("move-url"),
                    data: {
                        dY: C
                    },
                    success: function(F) {}
                })
            }
        },
        items: "> tr.p-group-row"
    });
    $(".draggable-rows.enabled").disableSelection();

    function e(C) {
        $(".group-field + ul", C).each(function() {
            if ($("li", this).length == 0) {
                $(this).parent().hide()
            } else {
                $(this).parent().show()
            }
        })
    }
    e($(".parameters .disabled"));

    function B(C, E) {
        var F = E.find("label").text();
        var D = null;
        C.find("label").each(function() {
            var G = $(this);
            if (G.text() > F) {
                return false
            }
            D = G
        });
        if (D) {
            E.insertAfter(D.parent("li"))
        } else {
            C.prepend(E)
        }
    }

    function d(E) {
        var D = E.find(".columns").find(".disabled"),
            C = E.find(".columns").find(".enabled.sortable");
        C.each(function() {
            if ($(this).find("input").length === 0) {
                $(this).parent().find(".disabled-label").removeClass("hide").siblings(".enabled ").addClass("hide")
            } else {
                $(this).parent().find(".disabled-label").addClass("hide").siblings(".enabled ").removeClass("hide")
            }
        });
        var F = D.parent();
        if (D.find("input").length === 0) {
            F.find(".unavailable").removeClass("hide");
            F.find(".available").addClass("hide")
        } else {
            F.find(".unavailable").addClass("hide");
            F.find(".available").removeClass("hide")
        }
    }
    $(".parameters .enabled").delegate('input[type="checkbox"]', "change", function() {
        var F = $(this).parents(".parameters");
        var G = $(this).parents("li");
        var D = F.find(".disabled");
        var C = $(this).parents(".enabled");
        var E = $('.group-field[data-group="' + G.data("group") + '"]', D);
        B(E.next("ul"), G);
        e(D);
        d(F);
        if (F.is(".not-null")) {
            if (C.children(":not(.group-field)").length == 1) {
                C.find("input[type=checkbox]").each(function() {
                    var H = $('<input type="hidden">');
                    H.attr("name", $(this).attr("name")).attr("value", $(this).attr("value"));
                    $(this).attr("disabled", true).after(H)
                })
            }
        }
    });
    $(".parameters .disabled").delegate('input[type="checkbox"]', "change", function() {
        var E = $(this).parents(".parameters"),
            G = $(this).parents("li"),
            F = G.data("block"),
            C;
        if (F) {
            C = E.find(".enabled.sortable[data-block=" + F + "]")
        } else {
            C = E.find(".enabled.sortable")
        }
        var D = $(this).parents(".disabled");
        C.append(G);
        e(D);
        d(E);
        if (E.is(".not-null")) {
            C.find("input[type=checkbox]").attr("disabled", false).prev('input[type="hidden"]').remove()
        }
    });
    $(".parameters a.reset").click(function() {
        var E = $(this).data("confirm-text");
        if (confirm(E)) {
            var D = '<form method="POST" action="' + $(this).attr("href") + '" >';
            if ($(this).data("redirect-here") !== undefined) {
                D += '<input type="hidden" name="url" value="' + window.location.toString() + '">'
            }
            D += "</form>";
            var C = $(D);
            $("body").append(C);
            C[0].submit()
        }
        return false
    });
    $(".list-export").click(function() {
        $(".export-modal").find(".loader").remove();
        $(".export-modal").trigger("click");
        return false
    });
    y.find(".export-tpl-create").live("click", function(C) {
        C.preventDefault();
        $(this).parent().addClass("hide").parent().find(".control-group.hide").removeClass("hide")
    });
    y.find(".export-tpl-cancel").live("click", function(C) {
        C.preventDefault();
        $(this).parent().addClass("hide").parent().find(".controls.hide").removeClass("hide")
    });
    y.find(".export-tpl-apply").live("click", function(J) {
        J.preventDefault();
        var I = $(this),
            M = I.data("format"),
            G = I.data("columns").split("|"),
            O = I.data("format-options"),
            H = G.indexOf("explode_address") >= 0,
            D = $("#listexport_detailAddressColumns").find("input").map(function() {
                return $(this).val()
            }).toArray(),
            N, F, E = function(P) {
                return ("" + P).replace(/([a-z])([A-Z])/, "$1_$2").toLowerCase()
            };
        y.find("input:radio, input:checkbox").each(function() {
            var T = $(this),
                P = T.attr("name"),
                Q = P.match(/^[^\[]*\[([^[]+)\]/)[1],
                S = T.val(),
                R = E(S);
            switch (Q) {
                case "format":
                    T.prop("checked", S == M);
                    if (T.prop("checked")) {
                        T.change()
                    }
                    break;
                case "detailAddressColumns":
                    if (H) {
                        if ("text" == S) {
                            S = "delivery_address";
                            R = "address"
                        }
                        T.prop("checked", G.indexOf(S) >= 0 || G.indexOf(R) >= 0)
                    }
                    break;
                case "columns":
                    T.prop("checked", !(H && D.indexOf(S) !== -1) && (G.indexOf(S) >= 0 || G.indexOf(R) >= 0));
                    if ("columns" == Q && (S == "delivery_address" || S == "address")) {
                        F = T;
                        if (H) {
                            T.prop("checked", true)
                        }
                    }
                    break;
                case M + "Options":
                case "csvDelimiter":
                    switch (T.attr("type")) {
                        case "radio":
                            T.prop("checked", T.val() == O[Q.substring(M.length).toLowerCase()]);
                            break;
                        case "checkbox":
                            T.prop("checked", O[T.val()]);
                            break
                    }
                    break;
                case "explodeAddress":
                    N = T;
                    T.prop("checked", H)
            }
        });
        $(F).add(N).change();
        l(y);
        var C = y.find(".enabled.sortable");
        var K = C.children("li");
        var L = function(R) {
            var Q = G.indexOf(R);
            var P = ["delivery_address", "address"];
            if (-1 === Q && -1 !== P.indexOf(R)) {
                Q = G.indexOf("explode_address")
            }
            return Q
        };
        K.sort(function(Q, P) {
            Q = L($(Q).find("input").val());
            P = L($(P).find("input").val());
            if (Q > P) {
                return 1
            } else {
                if (Q < P) {
                    return -1
                }
            }
            return 0
        });
        K.detach().appendTo(C);
        s(y);
        if ($("#listexport_csvDelimiter input:checked").length == 0) {
            $("#listexport_csvDelimiter input").first().prop("checked", true)
        }
    });
    y.find(".export-tpl-del").live("click", function(G) {
        G.preventDefault();
        G.stopPropagation();
        var D = $(this),
            C = D.data("url"),
            F = D.closest("li"),
            E = F.closest("ul");
        if (!C) {
            return
        }
        $.ajax({
            type: "post",
            url: C
        });
        if (E.find("li").length <= 1) {
            F = E.closest(".template-list")
        }
        F.fadeOut(200, function() {
            F.remove()
        })
    });
    y.find("form").submit(function() {
        var D = $("form.default-form-filter");
        var G = $(this);
        if (0 >= G.find('div.columns ul.sortable.enabled input[type="checkbox"]').length) {
            alert(_translate("alert.not_selected_fields"));
            return false
        }
        if (G.is(".active")) {
            return false
        }
        G.addClass("active");
        if (y.find(".export-tpl-name").hasClass("hide")) {
            y.find(".export-tpl-name input").val("")
        }
        $('input[name="filter[ids][]"]', G).remove();
        var K = getCheckedIds(),
            C = K.ids;
        if (C != "all") {
            for (var L in C) {
                $('<input type="hidden">').appendTo(G).attr("name", "filter[ids][]").val(C[L])
            }
        }
        var F = getFilterData(D);
        for (var E in F) {
            if (E.substr(-2) == "[]") {
                for (var I in F[E]) {
                    $('<input type="hidden">').appendTo(G).attr("name", E).val(F[E][I])
                }
            } else {
                $('<input type="hidden">').appendTo(G).attr("name", E).val(F[E])
            }
        }
        var J = null,
            N = null;
        var M = location.search.match(/(&|\?)filter%5Bsort%5D=([^&]*)/);
        if (M && M.length === 2 && M[2]) {
            J = M[2];
            M = location.search.match(/(&|\?)filter%5Bdirection%5D=([^&]*)/);
            if (M && M.length === 2 && M[2]) {
                N = M[2]
            }
        }
        if (J) {
            $('<input type="hidden">').appendTo(G).attr("name", "filter[sort]").val(J)
        }
        if (N) {
            $('<input type="hidden">').appendTo(G).attr("name", "filter[direction]").val(N)
        }
        $("form", y).css("visibility", "hidden");
        $("#list-export-popup").scrollTop(0);
        var H = $('<div class="overpage o-bg-black black-red-loader"></div>');
        y.append(H);
        $(".close", y).click();
        setTimeout(function() {
            G.removeClass("active");
            $("form", y).css("visibility", "visible");
            $(".stat-box-popup-content", y).attr("style", "");
            y.find(".black-red-loader").remove();
            var O = y.find(".export-tpl-place");
            if (O.find(".control-group.hide").length) {
                return
            }
            y.find(".export-tpl-activate").removeClass("hide");
            y.find(".export-tpl-name").addClass("hide").find("input").val("");
            if (O.length) {
                $.ajax({
                    type: "post",
                    url: O.data("url"),
                    dataType: "html",
                    success: function(P) {
                        O.parent().replaceWith(P);
                        O.parents("li").removeClass("hide")
                    }
                })
            }
        }, 2000)
    });
    $("#listexport_format input").change(function() {
        var C = $("#listexport_format input:checked").val();
        $(".format-option[data-group]", y).hide().filter('[data-group="' + C + '"]').each(function() {
            if ($("input", this).length) {
                $(this).show()
            }
        })
    });
    var n = $("#listexport_explodeAddress");
    if (n.length) {
        n.parents("li").show();
        n.change(function() {
            if ($(this).is(":checked")) {
                $("#listexport_detailAddressColumns").show()
            } else {
                $("#listexport_detailAddressColumns").hide()
            }
        }).change();
        $('.export-modal input[value="delivery_address"],            .export-modal input[value="address"]').change(function() {
            if ($(this).is(":checked")) {
                n.parents("li").show()
            } else {
                n.parents("li").hide()
            }
        }).change()
    }
    $("#listexport_format input:checked").change();
    $("#multiple-custom-field-change .button a").live("click", function(D) {
        var C = $("#custom-fields-batch-popup");
        C.intaroPopup({
            url: $(this).parents(".save_batch").data("url"),
            data: {
                field_code: $(this).parent().data("field-code")
            },
            onSuccess: function() {
                $(".datepicker:not(.datepicker-custom),.date:not(.datepicker-custom)", C).datepicker({
                    dateFormat: _getDateFormatString()
                });
                $(".datepicker", C).each(function() {
                    addDateIcon($(this))
                })
            }
        })
    });
    $("#custom-fields-batch-form").live("submit", function() {
        var D = $(this);
        var C = $("#custom-fields-batch-popup");
        $("button", D).attr("disabled", "disabled");
        $.ajax({
            url: D.attr("action"),
            type: "POST",
            data: $.param(getCheckedIds()) + "&" + D.serialize(),
            beforeSend: function() {
                $(".ajax-progress", D).css("display", "inline-block")
            },
            complete: function() {
                $(".ajax-progress", D).hide()
            },
            error: function(E) {
                alert(CRM_ERROR_MESSAGE);
                C.intaroPopup("close")
            },
            success: function(G, F, E) {
                if (E.status == 202) {
                    C.intaroPopup("close");
                    window.location = G;
                    return
                }
                C.intaroPopup("setContent", G);
                $(".datepicker:not(.datepicker-custom),.date:not(.datepicker-custom)", C).datepicker({
                    dateFormat: _getDateFormatString()
                });
                $(".datepicker", C).each(function() {
                    addDateIcon($(this))
                })
            }
        });
        return false
    });
    $("table.batch-tbl th.batch-container > i").click(function(C) {
        $(this).next().show();
        C.stopPropagation()
    });
    $("html").click(function() {
        $("table.batch-tbl th.batch-container .batch-popup").hide()
    });
    var A = function() {
        var C = 0;
        if ($("#batch_all").val() == 1) {
            C = parseInt($("#list-total-count > span").text())
        } else {
            C = $(".batch-element-checkbox:checked").length
        }
        $("#list-batch-form .batch-warning.count-warning").each(function() {
            var E = $(this);
            var D = parseInt(E.data("count"));
            if (C > D) {
                E.addClass("active").fadeIn()
            } else {
                E.removeClass("active").hide()
            }
        })
    };
    var h = function() {
        var C = $("table.batch-tbl"),
            D = C.find('input[name^="batch[element]"]'),
            E = D.filter(":checked");
        if (D.length > E.length) {
            $("#batch_all").val("")
        }
        if (E.length > 0) {
            $(".batch-box").slideDown(200);
            if ($("#batch_all").val() == 1) {
                $(".batch-box .elements-count").html(_translate("info.checked_all"))
            } else {
                $(".batch-box .elements-count").html(_transchoice(E.length, "info.checked_elements"))
            }
        } else {
            $(".batch-box").slideUp(200, function() {
                $(".batch-box .elements-count").html("")
            })
        }
        t(C, D, E);
        A()
    };
    var g = "";
    $("table.batch-tbl th.batch-container .batch-popup li").live("click", function() {
        var C = $('table.batch-tbl tbody input[type=checkbox][name^="batch[element]"]');
        if (C.attr("checked") == "checked" && $(this).attr("class") == g) {
            C.removeAttr("checked");
            $("#batch_all").val("")
        } else {
            C.attr("checked", "checked");
            g = $(this).attr("class");
            if ($(this).hasClass("batch-select-all")) {
                $("#batch_all").val(1)
            } else {
                $("#batch_all").val("")
            }
        }
        h()
    });
    $("table.batch-tbl").delegate(':checkbox[id^="batch_element_"]', "change", h);
    $upload = $("#upload");
    var m = function() {
        var D = $upload.attr("data-datetime");
        var C = location.search.substr(1);
        if (C.length) {
            C += "&"
        }
        C += "start_datetime_point=" + D;
        return C
    };
    var v;
    var r = function() {
        var D = $("#upload-btn");
        if (D.is(".loading")) {
            v = setTimeout(r, globalSettings.ajaxUpdateNewListDelay);
            return
        }
        var C = $upload.data("check");
        $.ajax({
            url: C,
            data: m(),
            method: "GET",
            dataType: "json",
            success: function(F) {
                var E = F.count;
                $upload.find("span").text(F.message);
                if (E > 0) {
                    $upload.find("div").slideDown(400, "swing")
                }
                v = setTimeout(r, globalSettings.ajaxUpdateNewListDelay)
            }
        })
    };
    if ($upload.length) {
        v = setTimeout(r, globalSettings.ajaxUpdateNewListDelay)
    }
    $("#upload-btn").click(function() {
        var C = $(this);
        if (C.is(".loading")) {
            return false
        }
        C.addClass("loading");
        var D = $upload.data("update");
        $.ajax({
            url: D,
            type: "GET",
            data: m(),
            success: function(J) {
                var G = $("<table>");
                G.html(J);
                var F = $("#upload", G),
                    I = F.attr("data-datetime"),
                    H = F.attr("data-total-summ"),
                    L = F.attr("data-total-margin"),
                    K = F.attr("data-total-count");
                $upload.attr("data-datetime", I);
                $upload.attr("data-total-summ", H);
                $upload.attr("data-total-margin", L);
                $upload.attr("data-total-count", K);
                $("#list-total-summ > span:first").text(H);
                $("#list-total-margin > span:first").text(L);
                $("#list-total-count > span:first").text(K);
                var E = G.find("tr:gt(0)");
                E.addClass("ajax-new");
                $.when($upload.parents("tr").after(E)).then(function() {
                    C.parent().slideUp(400, "swing");
                    window.setTimeout(function() {
                        $("tr.ajax-new").hover(function() {
                            $(this).removeClass("ajax-new")
                        })
                    }, 3000);
                    $(".message-indic", E).click(commentIconClick)
                })
            },
            complete: function() {
                C.removeClass("loading")
            }
        });
        return false
    });
    $(".folder-link, .pg-child.pg-link ").click(function() {
        $("#filter_parentGroupId").val($(this).parents("tr").data("group-id"));
        $("#filter_root").val($(this).parents("tr").data("group-root"));
        $("#filter_lvl").val($(this).parents("tr").data("group-lvl") + 1);
        $("form.default-form-filter").submit();
        return false
    });
    $(".m-pagination ul.triangle>li a").click(function() {
        $("#filter_parentGroupId").val($(this).parent().data("group-id"));
        $("#filter_root").val($(this).parent().data("group-root"));
        $("#filter_lvl").val($(this).parent().data("group-lvl") + 1);
        $("form.default-form-filter").submit();
        return false
    });
    $(".parameters-toggle .check").click(function() {
        $(this).parents("div.table.columns").find("input[type=checkbox]").prop("checked", true);
        l(y);
        s(y)
    });
    $(".parameters-toggle .un-check").click(function() {
        $(this).parents("div.table.columns").find("input[type=checkbox]").prop("checked", false);
        l(y);
        s(y)
    });
    $(".categories-list__item").live("click", function(C) {
        if (!$(C.target).is('[type="checkbox"]')) {
            C.stopPropagation()
        }
    });
    $(".categories-list__item_group").live("click", function(C) {
        C.stopPropagation();
        $(this).toggleClass("open");
        $(this).parents(".categories-list__item:first").toggleClass("open")
    });
    $(".categories-list__item_title").live("dblclick", function(C) {
        $group = $(this).children(".categories-list__item_group");
        if ($group.length > 0) {
            C.stopPropagation();
            $group.toggleClass("open");
            $group.parents(".categories-list__item:first").toggleClass("open")
        }
    });
    $(".categories-list_main .categories-list__check").live("change", function() {
        var C = $(".categories-list_main");
        var D = C.find(".categories-list__check");
        t(C, D)
    });
    $(".categories-list__item_group > .categories-list__check").live("change", function() {
        var E = $(this);
        var C = $(".categories-list_main");
        var D = C.find(".categories-list__check");
        E.closest(".categories-list__item_group").find(".categories-list__check").prop("checked", E.prop("checked"));
        t(C, D)
    });

    function t(I, G, E) {
        if (I.data("batch")) {
            var C = $("#" + I.data("batch")),
                D = C.find(".btn"),
                F = C.find(".form-reset-btn"),
                J = C.find(".batch-counter"),
                H = E || G.filter(":checked");
            J.find(".batch-number").text(H.length);
            if (H.length > 0) {
                J.removeClass("hide");
                D.removeAttr("disabled")
            } else {
                J.addClass("hide");
                D.attr("disabled", "")
            }
            F.live("click", function() {
                G.removeAttr("checked");
                J.addClass("hide")
            })
        }
    }
});

function getCheckedIds(a) {
    a = typeof a !== "undefined" ? a : true;
    var b = {};
    if (a) {
        b = getFilterData($(".default-form-filter"))
    }
    b["filter[sort]"] = $("#filter_sort").val();
    b["filter[direction]"] = $("#filter_direction").val();
    if ($("#batch_all").length && $("#batch_all").val() == 1) {
        b.ids = "all";
        b.excluded_ids = [];
        $(".batch-tbl .batch-element-checkbox:not(:checked)").each(function() {
            var c = parseInt($(this).val());
            if (!isNaN(c)) {
                b.excluded_ids.push(c)
            }
        })
    } else {
        b.ids = [];
        $(".batch-tbl .batch-element-checkbox:checked").each(function() {
            var c = parseInt($(this).val());
            if (!isNaN(c)) {
                b.ids.push(c)
            }
        })
    }
    return b
}

function getScrollbarWidth() {
    var d = document.createElement("div");
    d.style.visibility = "hidden";
    d.style.width = "100px";
    document.body.appendChild(d);
    var b = d.offsetWidth;
    d.style.overflow = "scroll";
    var a = document.createElement("div");
    a.style.width = "100%";
    d.appendChild(a);
    var c = a.offsetWidth;
    d.parentNode.removeChild(d);
    return b - c
};
(function(h, m, k) {
    if (m.NotificationBox) {
        return
    }
    var r = "IntaroCrmHtml5SendData";
    var g = "IntaroCrmNotification";
    var c = function(s) {
        var e = this;
        s = s || {};
        s = h.extend({
            box: null,
            templateId: "notification-template-common",
            messageLifetime: 10,
            missedCallDelay: 20,
            voiceNotifications: true,
            onAdd: null,
            onClose: null
        }, s);
        this.box = h(s.box);
        this.templateId = s.templateId;
        this.messageLifetime = s.messageLifetime;
        this.missedCallDelay = s.missedCallDelay;
        this.voiceNotifications = s.voiceNotifications;
        this.onAdd = s.onAdd;
        this.onClose = s.onClose;
        this._clearHtml5Notifications();
        this._loadFromLocalStorage();
        h(document).delegate(h(".close", this.box).selector, "click", h.proxy(this, "_close"));
        h(document).delegate(h(".notification_min .notification__content", this.box).selector, "click", function() {
            h(this).parent().toggleClass("notification_open")
        })
    };
    var q;
    var n = document.title;
    var p = 0;
    var j;
    try {
        j = new Favico({
            animation: "none",
            type: "rectangle",
            bgColor: "#cc0000",
            fontFamily: "Arial, Helvetica, sans-serif",
            position: "down"
        })
    } catch (l) {}
    var o = function() {
        p = 0;
        document.title = n;
        if (j) {
            try {
                j.reset()
            } catch (s) {}
        }
    };
    q = function() {
        p += 1;
        document.title = "[" + p + "] " + n;
        if (j) {
            try {
                j.badge(p)
            } catch (t) {}
        }
        var s = function() {
            o();
            m.removeEventListener("focus", s)
        };
        m.addEventListener("focus", s)
    };
    h.extend(c.prototype, {
        _addToLocalStorage: function(w, t) {
            if (!a()) {
                return
            }
            var s = new Date();
            var v = m.localStorage.getItem(g);
            if (v) {
                v = m.JSON.parse(v)
            } else {
                v = {}
            }
            v[w] = {
                content: t,
                created: new Date()
            };
            try {
                m.localStorage.setItem(g, m.JSON.stringify(v))
            } catch (u) {
                if (u == QUOTA_EXCEEDED_ERR) {
                    delete m.localStorage[g]
                }
            }
        },
        _loadFromLocalStorage: function() {
            if (!a()) {
                return
            }
            var u = m.localStorage.getItem(g);
            if (u) {
                u = m.JSON.parse(u)
            } else {
                u = {}
            }
            for (var s in u) {
                this.box.append(u[s].content);
                var e = new Date(u[s].created);
                this._closeTimer(s, e);
                if (h(u[s].content).hasClass("notification_dial-in-notice")) {
                    this._dialinActions(s, e)
                }
            }
            this.box.children().show();
            var t = this;
            this.box.children().each(function() {
                if (h(this).offset().top < 0) {
                    h(".close a", h(this)).click()
                }
            })
        },
        _removeFromLocalStorage: function(u) {
            if (!a()) {
                return
            }
            var t = m.localStorage.getItem(g);
            if (t) {
                t = m.JSON.parse(t)
            } else {
                t = {}
            }
            if ("undefined" !== typeof t[u]) {
                delete t[u];
                try {
                    m.localStorage.setItem(g, m.JSON.stringify(t))
                } catch (s) {
                    if (s == QUOTA_EXCEEDED_ERR) {
                        delete m.localStorage[g]
                    }
                }
            }
        },
        _hide: function() {
            var t = h(this);
            var s = t.prev(".notification").stop(false, true);
            var e = parseInt(s.css("margin-bottom"), 10);
            var u = t.outerHeight(true);
            t.stop(false, true).fadeOut(200, function() {
                s.css("margin-bottom", (u + e) + "px").animate({
                    marginBottom: e + "px"
                }, 300);
                t.remove()
            })
        },
        _close: function(u, s, e) {
            var t = this;
            var v = "undefined" !== typeof s ? h(s) : h(u.currentTarget).parents(".notification");
            if ("undefined" !== typeof u && u && (u.shiftKey || u.ctrlKey)) {
                this.box.children().each(function() {
                    t._close(null, h(this), true)
                });
                return false
            }
            v.each(function() {
                t._hide.call(this)
            });
            var w = d(v);
            if (w) {
                if ((("undefined" !== typeof u && u) || ("undefined" !== typeof e && e)) && this.onClose && w[0] != "h") {
                    this.onClose(w)
                }
                this._removeFromLocalStorage(w)
            }
            return false
        },
        _closeTimer: function(w, s) {
            if ("undefined" === typeof w || !w) {
                return
            }
            if ("undefined" === typeof s) {
                s = new Date
            }
            var u = h("#notification-" + w + ".notification", this.box);
            if (!u.length || u.hasClass("important")) {
                return
            }
            var e = new Date;
            var v = this.messageLifetime * 1000 - (e.getTime() - s.getTime());
            var t = setTimeout(function() {
                h(".close a", u).click()
            }, v)
        },
        _voiceNotification: function(e) {
            if (this.voiceNotifications) {
                var s = e.hasClass("important") ? "important" : "common";
                f(s)
            }
        },
        _showHtml5Notification: function(s) {
            if (k.isActive()) {
                return
            }
            var e = this.messageLifetime * 1000;
            h.notification({
                body: i(s.message),
                title: "retailCRM",
                iconUrl: "/favicon.ico",
                onclick: function() {
                    m.focus()
                }
            }).then(function(t) {
                var u = setTimeout(function() {
                    t.close()
                }, e);
                h(m).one("focus", function() {
                    clearTimeout(u);
                    t.close()
                })
            });
            return Notification.permission === "granted"
        },
        _clearHtml5Notifications: function() {
            var v = m.JSON.parse(m.localStorage.getItem(r));
            if (v) {
                var s = 10 * 1000;
                var e = new Date;
                for (var u in v) {
                    var t = new Date(v[u]);
                    if (e - t > s) {
                        delete v[u]
                    }
                }
                m.localStorage.setItem(r, m.JSON.stringify(v))
            }
        },
        _dialinActions: function(w, s) {
            if ("undefined" === typeof w || !w) {
                return
            }
            if ("undefined" === typeof s) {
                s = new Date
            }
            var u = h("#notification-" + w + ".notification", this.box);
            if (!u.length) {
                return
            }
            var e = new Date;
            var v = this.missedCallDelay * 1000 - (e.getTime() - s.getTime());
            var t = setTimeout(function() {
                u.addClass("notification_min")
            }, v)
        },
        add: function(t) {
            if (!k.isLastActive()) {
                return
            }
            if (!k.isActive()) {
                q()
            }
            t.content = h.trim(t.content);
            var s = this;
            var e = h(t.content);
            var u = d(t.content);
            this.box.append(e);
            this.box.children().show();
            if (!u) {
                u = "h" + t.content.hashCode();
                e.attr("id", "notification-" + u)
            }
            if (e.hasClass("notification_dial-in-notice")) {
                this._dialinActions(u)
            }
            this.box.children().each(function() {
                if (h(this).offset().top < 0) {
                    h(".close a", h(this)).click()
                }
            });
            if (!this._showHtml5Notification(t)) {
                this._voiceNotification(e)
            }
            this._addToLocalStorage(u, e[0].outerHTML);
            this._closeTimer(u);
            if (u[0] != "h" && this.onAdd) {
                this.onAdd(u)
            }
        },
        close: function(t, e) {
            if ("undefined" === typeof t || !t) {
                return
            }
            var s = h("#notification-" + t + ".notification", this.box);
            if (s.length) {
                this._close(null, s, e)
            }
        },
        addErrorNotification: function(s) {
            var e = m.tmpl(this.templateId, {
                n: {
                    important: true,
                    error: true,
                    createdAt: moment().format("HH:mm"),
                    message: s
                }
            });
            this.add({
                message: i(s),
                content: e
            })
        }
    });

    function a() {
        try {
            return "localStorage" in m && m.localStorage !== null
        } catch (s) {
            return false
        }
    }
    var b = {};

    function f(t) {
        if (typeof b[t] == "undefined") {
            var s;
            if (t == "important") {
                s = "/sounds/message-alert-2"
            } else {
                s = "/sounds/menu-interface-1"
            }
            if ((new Audio()).canPlayType("audio/mpeg")) {
                s = s + ".mp3"
            } else {
                s = s + ".wav"
            }
            b[t] = new Audio(s)
        } else {
            try {
                b[t].currentTime = 0
            } catch (u) {
                return
            }
        }
        b[t].play()
    }

    function i(s) {
        var e = document.createElement("DIV");
        e.innerHTML = s;
        return e.textContent || e.innerText || ""
    }

    function d(e) {
        var s = h(e).attr("id");
        if ("undefined" !== typeof s && s.length) {
            return s.replace("notification-", "")
        }
        return null
    }
    m.NotificationBox = c
})(jQuery, window, tabWatcher);
(function(c, b) {
    if (b.PushstreamWrapper) {
        return
    }
    var a = function(e) {
        var d = this;
        e = e || {};
        e = c.extend({
            pushStreamHost: "",
            pushStreamPort: "",
            pushStreamChannel: "",
            pushStreamMode: "websocket",
            notificationBox: null
        }, e);
        this.notificationBox = e.notificationBox;
        this.pushstream = new PushStream({
            host: e.pushStreamHost,
            modes: e.pushStreamMode,
            useSSL: location.protocol == "https:"
        });
        this.pushstream.onmessage = c.proxy(this, "_messageReceived");
        this.pushstream.addChannel(e.pushStreamChannel);
        this.pushstream.connect()
    };
    c.extend(a.prototype, {
        _messageReceived: function(d) {
            var e = b.JSON.parse(d);
            switch (e.type) {
                case "notification":
                    this.notificationBox.add(e.data);
                    break;
                default:
            }
        }
    });
    b.PushstreamWrapper = a
})(jQuery, window);
var browserIsNotSupportPushStream = function() {
    return $.browser.msie && $.browser.version < 10
};
$(document).ready(function() {
    $.notification.requestPermission(function() {});
    $("#ht-notice a").click(function() {
        var e = $(this).parent().next();
        e.intaroPopup({
            url: e.find(".s-tabs_popup li.active").data("update-url"),
            onError: function() {
                $("div.full.notifications", p).html("<div class='no-data'>" + _translate("message.error_on_load_try_later") + "</div>")
            }
        });
        return false
    });
    var c = function(e) {
        if (e.parent().hasClass("notifications")) {
            $("#send-notification").show();
            $("#create-task-from-widget").hide()
        } else {
            $("#send-notification").hide();
            $("#create-task-from-widget").show()
        }
    };
    $(document).delegate("#notifications-widget-tab a", "click", function(g) {
        var f = $(this).parents(".stat-box-popup.notifications");
        c($(this));
        f.intaroPopup("updateContent", {
            url: $(this).parent().data("update-url")
        });
        jsTabItems($(this), g)
    });
    var a = $.cookie("s-tabs-notifications-widget-tab");
    if (a) {
        $("#notifications-widget-tab li").removeClass("active");
        $('#notifications-widget-tab li[data-update-url="' + a + '"]').addClass("active")
    }
    c($("#notifications-widget-tab li.active a"));
    $(".stat-box.notifications button.mark-as-read").live("click", function(g) {
        var f = $(this).parents(".stat-box-popup.notifications");
        f.intaroPopup("updateContent", {
            url: f.find(".s-tabs_popup li:first-child").data("update-url"),
            data: {
                markAllAsRead: 1
            }
        });
        jsTabItems(f.find(".s-tabs_popup li:first-child a"), g)
    });
    changeTaskCounter = function(f) {
        if ("undefined" === typeof f) {
            f = 1
        }
        f = parseInt(f);
        if (!f) {
            f = 1
        }
        var e = $("#tab-task-incomplete span");
        var g;
        if (e.length) {
            g = e.text().match(/\d+/)[0]
        }
        if (!g) {
            g = 0
        } else {
            g = parseInt(g)
        }
        if ((g + f) > 0) {
            $("#tab-task-incomplete span").html((g + f))
        } else {
            $("#tab-task-incomplete span").html("")
        }
    };
    changeNotificationCounter = function(f) {
        if ("undefined" === typeof f) {
            f = 1
        }
        f = parseInt(f);
        if (!f) {
            f = 1
        }
        var h = $("#ht-notice");
        var e = $("a > span:not(.d-hover__drop)", h);
        var g;
        if (e.length) {
            g = $("a > span", h).text().match(/\d+/)[0]
        }
        if (!g) {
            g = 0
        } else {
            g = parseInt(g)
        }
        if ((g + f) > 0) {
            $("#ht-notice > a").html("<span>" + (g + f) + "</span>");
            $("#tab-notification-unread span").html((g + f))
        } else {
            $("#ht-notice > a").html("");
            $("#tab-notification-unread span").html("")
        }
    };
    markNotificationAsRead = function(f) {
        var g = f.attr("data-id");
        var e = $("#notification-box").find('.notification[data-id="' + g + '"]');
        if (f.hasClass("notice-unread")) {
            f.removeClass("notice-unread");
            f.addClass("notice-read");
            changeNotificationCounter(-1)
        } else {
            f.addClass("notice-unread");
            f.removeClass("notice-read");
            changeNotificationCounter(1)
        }
        ajaxMarkNotificationAsRead(g)
    };
    ajaxMarkNotificationAsRead = function(e) {
        $.ajax({
            url: $(".stat-box-popup.notifications").attr("data-read-url"),
            data: {
                id: e
            },
            type: "POST",
            dataType: "json"
        })
    };
    $(".stat-box.notifications tr.notification:not(.is-task.task-tbl-complete)").live("click", function(e) {
        markNotificationAsRead($(this));
        return false
    });
    $(".stat-box.notifications tr.notification a, .stat-box.notifications tr.is-task a").live("click", function(e) {
        var f = $(this).parents("tr");
        if (f.hasClass("notice-unread")) {
            markNotificationAsRead(f)
        }
        e.stopPropagation()
    });
    $(".stat-box.notifications a.more-notifications").live("click", function() {
        var e = $(this);
        if (!e.hasClass("disabled")) {
            e.addClass("disabled");
            var g = $(this).parents(".stat-box-popup.notifications");
            var f = e.data("page");
            $.ajax({
                url: g.find(".s-tabs_popup li.active").data("update-url"),
                data: {
                    page: f
                },
                type: "POST",
                dataType: "json",
                success: function(i) {
                    if (i.list === "") {
                        $("div.full.notifications", g).html('<div class="no-data">' + _translate("message.no_notifications") + "</div>");
                        return
                    }
                    var j = e.parents("table").find(".task-tbl-day").last();
                    e.parents("tr").prev().before(i.list);
                    var h = e.parents("table").find(".task-tbl-day.first").last();
                    if ($.trim(h.text()) == $.trim(j.text())) {
                        h.remove()
                    }
                    e.data("page", f + 1);
                    e.removeClass("disabled");
                    if (i.showMoreLink === false) {
                        e.parents("tr").remove()
                    }
                    return
                },
                error: function() {
                    $("div.full.notifications", g).html("<div class='no-data'>" + _translate("message.error_on_load_try_later") + "</div>")
                }
            })
        }
        return false
    });
    $(".stat-box.notifications span.show-future-tasks").live("click", function() {
        var g = $(this);
        var f = g.closest(".notification-popup-list_future");
        var e = g.parents("tr");
        if (!g.hasClass("disabled")) {
            g.addClass("disabled");
            var h = $("div.stat-box.notifications");
            if (!e.next("tr").length > 0) {
                $.ajax({
                    url: g.data("load-url"),
                    type: "POST",
                    dataType: "html",
                    success: function(i) {
                        e.after(i);
                        return
                    },
                    error: function() {
                        $("div.full.notifications", h).html("<div class='no-data'>" + _translate("message.error_on_load_try_later") + "</div>")
                    }
                })
            } else {
                e.nextAll("tr").show()
            }
            f.addClass("notification-popup-list_future_open")
        } else {
            e.nextAll("tr").hide();
            g.removeClass("disabled");
            f.removeClass("notification-popup-list_future_open")
        }
        return false
    });
    $(document).ajaxSuccess(function() {
        var e = $(".stat-box-popup.notifications");
        var g = $("tr.hide[data-unread]", e);
        if (g.length) {
            var f = parseInt(g.data("unread"));
            if (f > 0) {
                $("#ht-notice > a").html("<span>" + f + "</span>");
                $("#tab-notification-unread span").html(f)
            } else {
                $("#ht-notice > a").html("");
                $("#tab-notification-unread span").html("")
            }
            g.remove()
        }
        var g = $("tr.hide[data-incomplete]", e);
        if (g.length) {
            var f = parseInt(g.data("incomplete"));
            if (f > 0) {
                $("#tab-task-incomplete span").html(f)
            } else {
                $("#tab-task-incomplete span").html("")
            }
            g.remove()
        }
    });
    $("#send-notification").click(function() {
        var e = $(".notification-send-form");
        if (e.hasClass("stat-box-popup")) {
            e.intaroPopup({
                url: e.attr("data-update-url"),
                dataType: "html",
                onError: function() {
                    $(".stat-box-content", e).html('<div class="no-data">' + _translate("message.error_on_load_try_later") + "</div>")
                }
            });
            $(this).parent().parent().hide()
        }
        return false
    });
    $(document).delegate(".notification-send-form form", "submit", function() {
        var e = $(this).parent(),
            f = $(this).attr("action");
        $.ajax({
            url: f,
            data: $(this).serialize(),
            type: "POST",
            complete: function(g) {
                if (g.status == 400 || g.status == 200) {
                    e.html(g.responseText);
                    if (g.status == 200) {
                        setTimeout(function() {
                            e.parent().find(".close").click();
                            $.get(f, function(h) {
                                e.html(h)
                            })
                        }, 2000)
                    }
                } else {
                    alert(_translate("alert.send_notification_error"))
                }
            }
        });
        $(this).find("button").addClass("disabled");
        return false
    });
    $(".task-tbl-mark-complete").live("click", function() {
        var e = $(this);
        e.removeClass("doing").removeClass("task-tbl-mark-complete").addClass("done").attr("title", "");
        if (e.parents("tr").hasClass("notice-unread")) {
            changeNotificationCounter(-1)
        }
        $.ajax({
            url: e.data("change-state-url"),
            type: "GET",
            data: {
                state: "done"
            },
            success: function(f) {
                e.parents("tr").attr("class", f.status + " strike");
                changeTaskCounter(-1);
                $(document).trigger("task:complete", {
                    initiator: this,
                    id: e.data("task")
                })
            },
            error: function() {
                e.hide();
                alert(CRM_ERROR_MESSAGE)
            }
        });
        return false
    });
    $(".stat-box.notifications").live("click", function(e) {
        return false
    });
    if (!browserIsNotSupportPushStream()) {
        var b = 20;
        if ("undefined" !== typeof crmTelephonyOptions && "undefined" !== typeof crmTelephonyOptions.params && "undefined" !== typeof crmTelephonyOptions.params.missedCallDelay && crmTelephonyOptions.params.missedCallDelay) {
            b = crmTelephonyOptions.params.missedCallDelay
        }
        var d = true;
        if (typeof notificationOptions != "undefined" && typeof notificationOptions.voiceNotifications != "undefined") {
            d = notificationOptions.voiceNotifications
        }
        notificationBox = new NotificationBox({
            box: $("#notification-box"),
            templateId: "notification_template_common",
            missedCallDelay: b,
            voiceNotifications: d,
            onClose: function(e) {
                ajaxMarkNotificationAsRead(e);
                changeNotificationCounter(-1);
                $('.stat-box-content.notifications .notification[data-id="' + e + '"]').removeClass("notice-unread").addClass("notice-read")
            },
            onAdd: function(e) {
                changeNotificationCounter(1)
            }
        });
        pushStreamOptions.notificationBox = notificationBox;
        pushstreamWrapper = new PushstreamWrapper(pushStreamOptions);
        $(document).delegate($(".stat-box-content.notifications .notification").selector, "click", function() {
            var e = $(this);
            if (e.hasClass("notice-read")) {
                notificationBox.close(e.data("id"))
            }
        })
    }
});
var showErrorNotification = function(a) {
    if ("undefined" === typeof notificationBox) {
        alert(a);
        return
    }
    notificationBox.addErrorNotification(a)
};
$(function() {
    $(".btn").live("mousedown", function() {
        if (!$(this).hasClass("disabled")) {
            $(this).addClass("click")
        }
    }).live("mouseup", function() {
        if (!$(this).hasClass("disabled")) {
            $(this).removeClass("click")
        }
    });
    enableHints();
    $(".save-box__close").live("click", function() {
        var b = $(this).closest(".save-box");
        var a = $(this).closest(".save-box__msg");
        if (!b.find("input[type=submit].btn").length) {
            b.fadeOut("fast");
            $(".m-box_last").removeClass("m-box_last")
        } else {
            a.fadeOut("fast")
        }
    })
});
var enableHints = function() {
    $(".modern .hint-action").hover(function() {
        var a = $(this).parent().find(".hint");
        if (!a.is(":visible")) {
            a.fadeIn()
        }
    }, function() {
        var a = $(this).parent().find(".hint");
        a.fadeOut()
    })
};
if ($.ui != undefined) {
    $.widget("intaro.autocomplete", $.ui.autocomplete, {
        _normalize: function(a) {
            if (a.length && a[0].label && (a[0].value || a[0].value == "")) {
                return a
            }
            return $.ui.autocomplete.prototype._normalize.call(this, a)
        },
        _renderItem: function(b, d) {
            var a = d.label;
            a = a.replace(new RegExp(escapeRegExp(this.term), "gi"), function(e) {
                return "<strong>" + e + "</strong>"
            });
            var c = $("<li></li>").data("item.autocomplete", d).attr("value", d.value).append(a);
            if (d.description != undefined && d.description != "") {
                c.append('<div class="ui-item-description">' + d.description + "</div>")
            }
            c.appendTo(b);
            if (d.value == -1) {
                c.addClass("ui-state-disabled")
            }
            return c
        }
    })
}
var enableAutocomplete = function(a) {
    if (a == undefined) {
        a = $('input[type="text"].autocomplete')
    } else {
        a = a.find('input[type="text"].autocomplete')
    }
    a.each(function() {
        var f = function() {
            var g;
            if ($(this).data("autocomplete-value-field")) {
                g = $("#" + $(this).data("autocomplete-value-field"));
                if (!g.length) {
                    g = $(this)
                }
            } else {
                g = $(this)
            }
            return g
        };
        if ($(this).autocomplete("instance") != undefined) {
            var c = f.call(this);
            var e = {};
            if ($(this).data("autocomplete-source") != undefined) {
                e = $(this).data("autocomplete-source");
                if (e instanceof Array && c != $(this)) {
                    for (var d = 0; d < e.length; d++) {
                        if (e[d]["value"] == c.val()) {
                            $(this).data("intaroAutocomplete")._trigger("select", "autocompleteselect", {
                                item: e[d]
                            });
                            break
                        }
                    }
                }
            }
            return
        }
        $(this).attr("autocomplete", "off");
        if ($(this).val() != "") {
            $(this).data("prev-value", $(this).val())
        }
        var c = f.call(this);
        var e = {};
        if ($(this).data("autocomplete-source") != undefined) {
            e = $(this).data("autocomplete-source");
            if (e instanceof Array && c != $(this)) {
                for (var d = 0; d < e.length; d++) {
                    if (e[d]["value"] == c.val()) {
                        $(this).val(e[d]["label"]);
                        break
                    }
                }
            }
        }
        var b = 1;
        if ($(this).data("autocomplete-min-length") != undefined) {
            b = $(this).data("autocomplete-min-length")
        }
        $(this).autocomplete({
            source: e,
            minLength: b,
            valueField: c,
            change: function(g, h) {
                if (h.item == null && $(this).autocomplete("instance").selectedItem == null) {
                    if ($(this).attr("data-autocomplete-strict") == false || $(this).val() == "") {
                        c.val("").change();
                        return
                    }
                    if ($(this).val() == "") {
                        c.val("").change()
                    } else {
                        if (c.val() != "") {
                            $(this).val($(this).data("prev-value"))
                        } else {
                            $(this).val("");
                            c.val("").change()
                        }
                    }
                }
            },
            focus: function(g, h) {
                this.selectedItem = null;
                return false
            },
            select: function(h, i) {
                var j = c.val();
                $(this).val(i.item.label);
                $(this).data("prev-value", i.item.label);
                c.val(i.item.value);
                $(this).autocomplete("instance").selectedItem = i.item;
                if (j != i.item.value) {
                    c.trigger("change", [i.item])
                }
                var g = h;
                while (g) {
                    if (g.keyCode == 13 || g.keyCode == 108) {
                        g.stopPropagation()
                    }
                    if (g == h.originalEvent) {
                        break
                    }
                    g = h.originalEvent
                }
                return false
            },
            search: function(g, h) {
                if (!$(this).is(":visible")) {
                    g.stopPropagation();
                    return false
                }
                $(this).parent().addClass("loading")
            },
            response: function(g, h) {
                $(this).parent().removeClass("loading");
                if (h.content.length == 1 && h.content[0].success != undefined && h.content[0].success == false) {
                    if (h.content[0].error != undefined) {
                        console.warn("Failed to get autocomplete data: %s", h.content[0].error)
                    }
                    return false
                }
                if (h.content.length == 0) {
                    h.content.push({
                        value: -1,
                        label: _translate("info.nothing_found")
                    })
                }
            },
            close: function(g) {
                if ($(this).val() != "") {
                    this.blur()
                }
                var h = $(this);
                window.setTimeout(function() {
                    h[0].focus()
                }, 0)
            }
        });
        $(this).click(function(g) {
            $(this).focus();
            if ($(this).autocomplete("option", "disabled")) {
                return
            }
            if (b === 0) {
                $(this).autocomplete("search", "");
                g.stopPropagation()
            }
        });
        $(this).siblings("div").click(function(g) {
            $autocomplete = $(this).siblings("input.autocomplete");
            $autocomplete.focus();
            if ($autocomplete.autocomplete("option", "disabled")) {
                return
            }
            if (b === 0) {
                $autocomplete.autocomplete("search", "");
                g.stopPropagation()
            } else {
                if ($autocomplete.val().length >= b) {
                    $autocomplete.autocomplete("search", $autocomplete.val())
                }
            }
            g.stopPropagation()
        })
    })
};

function invokeAutocompleteWithTerm(a, b) {
    if (!a.is(":visible")) {
        return false
    }

    function c(f, g) {
        var d = $(f.target);
        var e = d.data("intaroAutocomplete").menu.element.find("li");
        if (d.data("strict") == false && (e.length == 0 || (e.length == 1 && e[0].classList.contains("ui-state-disabled")))) {
            d.autocomplete("close");
            return
        }
        if (e.length == 1 && !e[0].classList.contains("ui-state-disabled")) {
            $(e[0]).click()
        } else {
            d.focus();
            d.data("intaroAutocomplete").previous = d.data("prev-value")
        }
    }
    a.one("autocompleteopen", c);
    a.val(b);
    a.autocomplete("search", b)
}

function addCollectionFormRow(e, d) {
    if (typeof d == "undefined") {
        d = {
            itemClass: "item-group"
        }
    }
    if (d.name == undefined) {
        d.name = "__name__"
    }
    var c = e.data("prototype");
    c = c.replace("__name__label__", "");
    var b = e.data("index");
    if (isNaN(b)) {
        b = e.find(":input[name]").length;
        e.data("index", b)
    }
    if ("replaces" in d) {
        for (replace in d.replaces) {
            if (d.replaces[replace] == "index") {
                c = c.replace(new RegExp(escapeRegExp(replace), "g"), b)
            } else {
                c = c.replace(new RegExp(escapeRegExp(replace), "g"), d.replaces[replace])
            }
        }
    }
    var a;
    while (true) {
        if ("index" in d && d.index != undefined) {
            a = $(c.replace(new RegExp(d.name, "g"), d.index))
        } else {
            a = $(c.replace(new RegExp(d.name, "g"), b))
        }
        if ($("#" + a.find("[id]:first").attr("id")).length == 0) {
            break
        }
        b += 1
    }
    e.data("index", b + 1);
    if (!("notAppend" in d) || !d.notAppend) {
        if (e.children("a.add-row").length) {
            e.children("a.add-row").before(a)
        } else {
            e.append(a)
        }
    }
    if (e.data("group-class") != undefined) {
        a.addClass(e.data("group-class"))
    }
    if ("itemClass" in d) {
        a.addClass(d.itemClass)
    }
    return a
}

function toggleInputRequired(a, b) {
    if (!a.is("input, select, textarea")) {
        a.find("input, select, textarea").each(function() {
            toggleInputRequired($(this), b)
        });
        return
    }
    if (b == undefined) {
        b = a.attr("data-required")
    } else {
        if (a.attr("data-required") == undefined) {
            $label = a.prev('label[for="' + a.attr("id") + '"');
            if (a.is("[required]") || ($label != undefined && $label.hasClass("required"))) {
                a.attr("data-required", true)
            } else {
                a.attr("data-required", false)
            }
        }
    }
    if (b == undefined) {
        return
    }
    $label = $('label[for="' + a.attr("id") + '"]');
    if ((b == "true" || b === true) && a.is(":visible")) {
        a.attr("required", true);
        if ($label.length) {
            $label.addClass("required")
        }
    } else {
        a.attr("required", false);
        if ($label.length) {
            $label.removeClass("required")
        }
    }
}

function ExcludingSelectGroup(c, b, a) {
    this.$selectCollection = c;
    this.values = {};
    this.$collectionContainer = b;
    this.$addButton = a;
    this.autoAddRow = true;
    this.init = function() {
        var d = this;
        if (this.$addButton == undefined) {
            this.autoAddRow = true
        } else {
            this.autoAddRow = false;
            this.$addButton.click(function(f) {
                f.preventDefault();
                $row = d.addRow();
                $row.find("select").change()
            })
        }
        this.$collectionContainer.children().each(function() {
            d.initRow($(this))
        });
        this.updateValues();
        if (this.autoAddRow && this.$collectionContainer && this.needEmpty()) {
            this.addRow()
        }
        this.updateOptions()
    };
    this.initRow = function(d) {
        var e = this;
        var f = d.find("select");
        if (!f.length) {
            return
        }
        f.change(function() {
            e.onSelectChange($(this))
        });
        this.$collectionContainer.trigger("selectCollection:initRow", [d, this])
    };
    this.updateOptions = function(e) {
        var d = this;
        if (e) {
            e.find("option").each(function() {
                if (d.values[$(this).attr("value")] == undefined) {
                    $(this).removeAttr("disabled")
                } else {
                    $(this).attr("disabled", "disabled");
                    $(this).removeAttr("selected")
                }
            })
        } else {
            this.$selectCollection.each(function() {
                var f = $(this).val();
                $(this).find("option").each(function() {
                    if (d.values[$(this).attr("value")] == undefined || f == $(this).attr("value")) {
                        $(this).removeAttr("disabled")
                    } else {
                        $(this).attr("disabled", "disabled")
                    }
                })
            })
        }
        if (this.$addButton) {
            if (this.needEmpty()) {
                this.$addButton.show()
            } else {
                this.$addButton.hide()
            }
        }
        this.$collectionContainer.trigger("selectCollection:updateOptions")
    };
    this.updateValues = function() {
        var d = this;
        d.values = {};
        this.$selectCollection.each(function() {
            if (!jQuery.contains(document, $(this)[0])) {
                d.$selectCollection = d.$selectCollection.not(['id = "' + $(this).attr("id") + '"']);
                return
            }
            if ($(this).val() != "") {
                d.values[$(this).val()] = $(this).val()
            }
        });
        return d.values
    };
    this.needEmpty = function() {
        var d = this;
        var e = true;
        if (this.$selectCollection.length == 0) {
            return true
        }
        this.$selectCollection.each(function() {
            if ($(this).val() == "") {
                e = false;
                return
            }
        });
        if (e) {
            if (Object.keys(d.values).length == this.$selectCollection.first().find('option:not([value = ""])').length) {
                e = false
            }
        }
        return e
    };
    this.onSelectChange = function(d) {
        this.updateValues();
        if (this.autoAddRow) {
            if (this.$collectionContainer) {
                if (d && d.val() == "") {
                    this.$selectCollection = this.$selectCollection.filter(":not(#" + d.attr("id") + ")");
                    d.parents(".control-group:first").remove()
                }
                if (this.needEmpty()) {
                    this.addRow()
                }
            }
        }
        this.updateOptions()
    };
    this.addRow = function() {
        var d = this;
        $row = addCollectionFormRow(this.$collectionContainer);
        this.$selectCollection = this.$selectCollection.add($row.find("select"));
        this.updateOptions($row.find("select"));
        this.initRow($row);
        return $row
    }
};
$(function() {
    $(".basicExample").timepicker();
    $(".show-next-block-link").live("click", function() {
        var a = $(this);
        if (a.hasClass("link-inline")) {
            a = a.parent()
        }
        a.hide().next().removeClass("hide").show().find(":input:not([type=hidden])").focus()
    });
    $(".stat-content .delete-field-link").live("click", function() {
        var a = $(this).parent();
        a.remove()
    });
    $(".stat-content .return-link").live("click", function() {
        var a = $(this);
        if (a.hasClass("set-complete")) {
            a.parents(".stat-box-popup").append($('<div class="overpage o-w o-bg black-red-loader"></div>'));
            $.ajax({
                url: a.data("change-state-url"),
                type: "GET",
                data: {
                    state: "done"
                },
                success: function(b) {
                    changeTaskPanelDigits("change", b.expiredChange, -1, 0);
                    loadList(a)
                }
            })
        } else {
            loadList(a)
        }
        return false
    });
    $(".stat-content .more").live("click", function() {
        $more = $(this);
        if (!$more.hasClass("disabled")) {
            $more.addClass("disabled");
            var a = $more.data("url");
            $more.html('<div class="small-loader"></div>');
            $.ajax({
                url: a,
                type: "GET",
                success: function(b) {
                    $more.parents("tr").before(b).remove()
                }
            })
        }
    });
    $(".inside-task-manager .independent-popup-open").click(function() {
        $(this).removeClass("active");
        var a = $("#independent-popup-" + $(this).data("type"));
        a.intaroPopup({
            url: a.data("url"),
            onSuccess: function(b) {
                dateAndTimeInit()
            }
        })
    });
    $(".independent-popup-open.dialin-task").live("click", function(c) {
        if (typeof $(this).data("type") !== "undefined" && typeof $(this).data("url") !== "undefined") {
            $(this).removeClass("active");
            var d = $("#independent-popup-" + $(this).data("type"));
            var a = $(this).data("url");
            var b = $(c.target).closest(".notification.dial-in-notice");
            if (b.size()) {
                $(".close", b).click()
            }
            d.intaroPopup({
                url: a,
                type: "GET",
                onSuccess: function(e) {
                    dateAndTimeInit()
                }
            })
        }
    });
    $(".stat-box-popup #add-task-form, .stat-box-popup #edit-task-form").live("submit", function(c) {
        var a = $(this);
        var b = a.attr("action");
        var d = a.closest("#independent-popup-dialin-reminder-add-bk");
        clearErrorMsgs(a);
        a.parent().before($('<div class="overpage o-w o-bg black-red-loader"></div>'));
        $.ajax({
            url: b,
            type: "POST",
            data: a.serialize(),
            dataType: "json",
            success: function(e) {
                if (e.status === "fail") {
                    a.parent().prev(".overpage").remove();
                    showErrorMsgs(a, e.errors)
                } else {
                    if (e.status === "success") {
                        if (d.size()) {
                            d.intaroPopup("close")
                        } else {
                            loadList($("#independent-popup-reminder-bk"));
                            changeTaskPanelDigits("set", e.expired, e.opened, e.total)
                        }
                    }
                }
            }
        });
        return false
    });
    $(document).delegate(".remind-hints span[data-time]", "click", function() {
        var a = $(this).parents(".remind-time-input");
        $.ajax({
            url: a.data("url").replace("__STRING__", $(this).data("time")),
            type: "GET",
            dataType: "json",
            success: function(b) {
                if (b.status === "success") {
                    $("input.add_task_date", a).val($.datepicker.formatDate(_getDateFormatString(), new Date(b.date)));
                    $("input.add_task_time", a).val(b.time)
                } else {
                    alert(CRM_ERROR_MESSAGE)
                }
            },
            error: function() {
                alert(CRM_ERROR_MESSAGE)
            }
        })
    });
    $(".task-modifier .tr-delete").live("click", function() {
        if (confirm("ֲû הויסעגטעוכüםמ ץמעטעו ףהאכטעü חאהאקף?")) {
            var c = $(this).parents("tr");
            var b = c.find(".tumbler").hasClass("doing"),
                a = c.data("delete-url");
            $prev = c.prev();
            if ($prev.hasClass("task-tbl-day") && (c.next().hasClass("task-tbl-day") || !c.next().length)) {
                $prev.remove()
            }
            $taskList = c.parent();
            c.remove();
            if (!$taskList.find(".task-tbl-day").length) {
                $taskList.append($('<tr><td colspan="6" class="no-data">' + _translate("message.you_have_no_tasks") + "</td></tr>"))
            }
            $.ajax({
                url: a,
                type: "POST",
                dataType: "json",
                success: function(d) {
                    if (d.status === "success") {
                        changeTaskPanelDigits("change", (d.wasExpired == "expired" ? -1 : 0), (b ? -1 : 0), -1)
                    } else {
                        alert(CRM_ERROR_MESSAGE)
                    }
                }
            })
        }
        return false
    });
    $(".task-modifier .tumbler").live("click", function() {
        var b = $(this);
        var a = b.hasClass("doing");
        b.removeClass(a ? "doing" : "done").addClass(a ? "done" : "doing").attr("title", (a ? _translate("info.check_as_not_completed") : _translate("info.check_as_completed")));
        $.ajax({
            url: b.parents("tr").data("change-state-url"),
            type: "GET",
            data: {
                state: (a ? "done" : "doing")
            },
            success: function(c) {
                changeTaskPanelDigits("change", c.expiredChange, (a ? -1 : 1), 0);
                b.parents("tr").attr("class", c.status + " task-modifier")
            },
            error: function() {
                b.hide();
                alert(CRM_ERROR_MESSAGE)
            }
        });
        return false
    });
    $("#task_page_complete").change(function() {
        var a = $(this).is(":checked");
        $(".tumbler-checker").removeClass("hide");
        $(".tumbler-checker." + (a ? "opener" : "closer")).addClass("hide");
        $(".tumbler.task-page-modifier").removeClass("done").removeClass("doing").addClass(a ? "done" : "doing")
    });
    $(".tumbler.task-page-modifier, .tumbler-checker").live("click", function() {
        $("#task_page_complete").click()
    });
    $("#tasks-show-btn").live("click", function() {
        var a = $(this);
        a.addClass("loading");
        $.ajax({
            url: a.data("updateUrl") + window.location.search,
            type: "GET",
            data: {
                page: a.attr("data-page")
            },
            success: function(c) {
                var d = a.parents("tr.task-tbl-future-shower").parent().find("tr.task-tbl-day");
                if (d.length) {
                    var b = d.first().attr("data-day")
                } else {
                    var b = null
                }
                a.parents("tr.task-tbl-future-shower").after(c);
                if (b) {
                    $thisDay = a.parents("tr.task-tbl-future-shower").parent().find('tr.task-tbl-day[data-day="' + b + '"]');
                    if ($thisDay.length > 1) {
                        $thisDay.last().remove()
                    }
                }
                a.removeClass("loading");
                a.parent().slideUp(400, "swing");
                window.setTimeout(function() {
                    $("tr.future").hover(function() {
                        $(this).removeClass("future")
                    })
                }, 3000)
            }
        });
        return false
    });
    $(".task-tbl .task-tbl-commentary").live("click", function() {
        $(this).find(".popup-comment-box").toggle();
        return false
    });
    $(".task-tbl .task-modifier a").live("click", function(a) {
        a.stopPropagation()
    });
    $(".task-tbl .task-modifier td").live("click", function() {
        var c = $(this);
        if (c.hasClass("no-click")) {
            return true
        }
        var e = c.parent();
        if (e.parents(".task-tbl").hasClass("non-ajax")) {
            window.location.href = e.data("edit-page-url");
            return false
        }
        var d = e.parents(".stat-box-popup");
        var b = parseInt(e.data("task"));
        var a = e.data("edit-url");
        d.intaroPopup({
            url: a,
            onSuccess: function(f) {
                dateAndTimeInit()
            }
        });
        return false
    });
    $(".stat-box-popup .close-task").live("click", function(a) {
        $(this).parents(".stat-box-popup").find(".return-link.hide").click();
        return false
    });
    $("#edit-task-form .remind-area .del-btn").live("click", function() {
        $btn = $(this);
        if (confirm($btn.data("alert"))) {
            $popup = $btn.parents(".stat-box-popup").append($('<div class="overpage o-w o-bg black-red-loader"></div>'));
            var a = $popup.find(".set-complete").length > 0;
            $.ajax({
                url: this.href,
                type: "POST",
                dataType: "json",
                success: function(b) {
                    if (b.status === "success") {
                        changeTaskPanelDigits("change", (b.wasExpired == "expired" ? -1 : 0), (a ? -1 : 0), -1)
                    }
                    loadList($btn)
                }
            })
        }
        return false
    })
});

function changeTaskPanelDigits(h, d, g, e) {
    var f = $(".inside-task-manager .inside-task-manager-count");
    if (!f.length) {
        return
    }
    if (!e) {
        e = 0
    }
    if (!g) {
        g = 0
    }
    d = parseInt(d);
    g = parseInt(g);
    e = parseInt(e);
    var a = $(".opened-tasks-text", f);
    var c = $(".total-tasks-text", f);
    var b = $(".expired-tasks-text", f);
    if (h == "change") {
        g = Math.max(parseInt(a.text()) + g, 0);
        e = Math.max(parseInt(c.text()) + e, 0);
        d = Math.max(parseInt(b.text()) + d, 0)
    }
    a.text(g);
    c.text(e);
    b.text(d);
    if (e > 0) {
        $("#has-tasks-panel").show();
        $("#no-tasks-panel").hide()
    } else {
        $("#has-tasks-panel").hide();
        $("#no-tasks-panel").show()
    }
    if (g > 0) {
        $(".inside-task-manager-count").show()
    } else {
        $(".inside-task-manager-count").hide()
    }
    if (d > 0) {
        $(".expired-tasks-count-block").show()
    } else {
        $(".expired-tasks-count-block").hide()
    }
}

function showErrorMsgs(b, d) {
    var c = b.attr("name");
    for (var e in d) {
        var g = "#" + c + "_" + e;
        $elem = $(g, b);
        if ($elem.length) {
            for (var f in d[e]) {
                var a = $("<div></div>");
                a.addClass("msg-error").text(d[e][f]);
                $elem.prev().before(a)
            }
        } else {
            var a = $("<div></div>");
            a.addClass("msg-error").text(d[e]);
            b.prepend(a)
        }
    }
}

function clearErrorMsgs(a) {
    $(".msg-error", a).remove()
}

function dateAndTimeInit() {
    $(".datepicker").datepicker({
        showOtherMonths: true
    });
    $(".date").datepicker({
        dateFormat: _getDateFormatString()
    });
    $(".date-icon").live("click", function() {
        if (!$("#ui-datepicker-div:visible").length) {
            $(this).prev(".datepicker").focus()
        }
    });
    $(".basicExample").timepicker()
}

function loadList(b) {
    var c;
    if (!b.hasClass("stat-box-popup")) {
        c = b.parents(".stat-box-popup")
    } else {
        c = b
    }
    var a = c.attr("data-url");
    if (!a) {
        c.intaroPopup("close");
        return
    }
    c.intaroPopup({
        url: a,
        onSuccess: function(d) {
            dateAndTimeInit();
            if (c.find(".no-data").length) {
                c.intaroPopup("close")
            }
        }
    })
};
$(document).ready(function() {
    var a = {};
    $(".call-record .actions").live("click", function() {
        var b = $(this).parent();
        playAudio(b, a)
    });
    $(".call-record .timer").live("click", function(f) {
        var d = $(this).parent();
        var c = $(this).offset();
        var b = f.pageX - c.left;
        playAudio(d, a, b)
    })
});

function playAudio(a, h, g) {
    var k = 52;
    var j = a.data("src");
    var d = a.data("id");
    if (a.hasClass("disable") || !j || !d) {
        return false
    }
    var l = $(".actions .action", a);
    var b = $(".timer", a);
    var i = $(".progress", b);
    i.removeClass("end");
    var c = $(".time", b);
    var e = l.data("action");
    if (g && e == "pause") {
        e = "start"
    }
    if (e == "load") {
        l.removeClass("load").addClass("start");
        l.data("action", "start");
        a.removeClass("active");
        if (h[d]) {
            h[d].pause();
            h[d].currentTime = 0
        }
        $(".call-record .actions .load").click();
        $(".call-record .actions .pause").click()
    }
    if (e == "start") {
        $(".call-record .actions .load").click();
        $(".call-record .actions .pause").click();
        a.addClass("active");
        l.removeClass("start").addClass("load");
        l.data("action", "load");
        if (!h[d]) {
            $player = new Audio();
            $player.src = j;
            h[d] = $player;
            h[d].load();
            h[d].addEventListener("error", function() {
                l.removeClass("load").removeClass("pause").addClass("start");
                a.removeClass("active");
                l.data("action", "error");
                showErrorNotification(_translate("message.telephony_call_record_fail"));
                return false
            });
            h[d].addEventListener("canplay", function() {
                if (l.data("action") == "load") {
                    h[d].play();
                    l.removeClass("start").removeClass("load").addClass("pause");
                    l.data("action", "pause")
                }
            });
            h[d].addEventListener("timeupdate", function() {
                if (!h[d].duration) {
                    return false
                }
                var n = parseInt(h[d].duration);
                var m = (h[d].currentTime * k) / n;
                var o = getCallRecordTimeLeft(h[d]);
                if (o !== false) {
                    o = "-" + o;
                    i.width(m);
                    if (i.width() > 51) {
                        i.addClass("end")
                    }
                } else {
                    l.removeClass("pause").addClass("start");
                    l.data("action", "start");
                    a.removeClass("active");
                    o = b.data("formatted");
                    i.width(0);
                    i.removeClass("end")
                }
                o = "&nbsp;&nbsp;&nbsp;" + o;
                c.html(o);
                i.html(o)
            })
        } else {
            var f = parseInt(h[d].duration);
            setPosition(h[d], g, k);
            h[d].play();
            l.removeClass("start").removeClass("load").addClass("pause");
            l.data("action", "pause")
        }
    }
    if (e == "pause") {
        l.removeClass("pause").addClass("start");
        l.data("action", "start");
        a.removeClass("active");
        if (h[d]) {
            h[d].pause()
        }
    }
}

function setPosition(b, a, c) {
    var d = parseInt(b.duration);
    if (a && d) {
        b.currentTime = (a * parseInt(d)) / c
    }
}

function getCallRecordTimeLeft(a) {
    var b = a.currentTime;
    var c = parseInt(a.duration);
    var d = c - b;
    if (d <= 0) {
        return false
    }
    return secToTimeString(d)
};

function showHelpPopUp(e, d, c, a) {
    var b = '<div class="stat-box-popup-bg telephony-box-overlay"></div>        <div class="stat-box-popup telephony-box">        <h2>' + e + '<div class="title">' + d + '</div></h2>        <div class="cl"></div>        <a class="close"><i></i></a>        <div class="stat-content">        <div class="help_content">' + c + "</div>        </div>        </div>";
    $(".telephony-help-boxs").append(b);
    if (a) {
        a = a - 250;
        if (a > 100) {
            $(".telephony-box").css({
                "margin-top": a
            })
        }
    }
    $(".telephony-box-overlay").show();
    $(".telephony-box").show()
}

function updateTimers() {
    $companyTimers = $(".telephony-company-ready .company-time");
    if ($companyTimers.length > 0) {
        setTimeout(function() {
            $companyTimers.each(function() {
                var b = $(this).data("sec");
                if (b - 1 > 0) {
                    var a = secToTimeString(b);
                    $(this).data("sec", b - 1);
                    $("span", $(this)).text(a)
                } else {
                    $(this).remove()
                }
            });
            updateTimers()
        }, 1000)
    }
}
$(document).ready(function() {
    $companyTimers = $(".telephony-company-ready .company-time");
    if ($companyTimers.length > 0) {
        updateTimers()
    }
    $(".telephony-company-ready .company-member").click(function() {
        var b = $(this).data("url");
        var c = $(this).data("id");
        var a = $(this).data("exclude");
        if (!b || !c) {
            return false
        }
        $obj = $(this);
        $obj.addClass("loading");
        $.ajax({
            url: b,
            type: "POST",
            data: {
                id: c,
                exclude: a
            },
            success: function(d) {
                $company = $obj.parent();
                if (a) {
                    $(".company-participate", $company).removeClass("hide");
                    $(".company-not-participate", $company).addClass("hide")
                } else {
                    $(".company-participate", $company).addClass("hide");
                    $(".company-not-participate", $company).removeClass("hide")
                }
                $obj.removeClass("loading")
            }
        });
        return false
    });
    $(".telephony-company-ready .close").click(function() {
        $parent = $(this).parent().parent();
        var a = $parent.data("cookies");
        $(".info", $parent).addClass("hide");
        $(".short", $parent).removeClass("hide");
        if (a) {
            $.cookie(a, 1, {
                path: "/"
            })
        }
        return false
    });
    $(".telephony-company-ready .short").click(function() {
        $parent = $(this).parent();
        var a = $parent.data("cookies");
        $(this).addClass("hide");
        $(".info", $parent).removeClass("hide");
        if (a) {
            $.cookie(a, 0, {
                path: "/"
            })
        }
        return false
    });
    $(".telephony-company-ready .help").click(function() {
        var e = $(this).data("sub-title");
        var d = $(this).data("title");
        var c = $(this).data("text");
        var b = $(this).offset();
        var a = $(".telephony-company-ready .info");
        showHelpPopUp(e, d, c, b.top);
        return false
    });
    $(".telephony-help-boxs .telephony-box .close").live("click", function() {
        $(this).parent().remove();
        $(".telephony-box-overlay").remove();
        return false
    })
});
(function(d, a) {
    var e, c, b, f;
    e = function() {
        var g = {};
        this.connect = function(h) {};
        this.init = function(h, i) {
            if ("undefined" !== typeof h.urls.makeCall) {
                g.makeCall = h.urls.makeCall
            }
            i()
        };
        this.isCallAvailable = function() {
            return "undefined" !== typeof g.makeCall
        };
        this.makeCall = function(h, i, j) {
            d.ajax({
                url: g.makeCall,
                type: "POST",
                dataType: "json",
                data: {
                    phone: h,
                    fromSite: i
                },
                success: function(k) {
                    if (!k.success) {
                        showErrorNotification(k.message)
                    }
                },
                error: function() {
                    showErrorNotification(_translate("message.telephony_call_fail"))
                },
                complete: j
            })
        }
    };
    c = function() {
        var h = null,
            l, k, j, m = {},
            g = true;

        function i(o) {
            if (g && typeof console !== "undefined" && typeof console.log !== "undefined") {
                console.log("ProstieZvonkiTelephony: " + o)
            }
        }

        function n(o) {
            return o.toString().replace(/\D/g, "")
        }
        this.connect = function(o) {
            if (h.isConnected()) {
                return
            }
            h.onConnect(function() {
                i("connected");
                if (typeof o !== "undefined") {
                    o()
                }
            });
            h.connect({
                user_phone: j,
                host: l,
                client_id: k,
                client_type: "retailcrm"
            })
        };
        this.init = function(o, p) {
            m.dialIn = o.urls.dialIn;
            m.logCall = o.urls.logCall;
            l = o.host;
            k = o.pass;
            j = o.managerCode;
            h = pz;
            this.connect(p);
            h.onEvent(function(q) {
                i((q.direction ? "outgoing" : "incoming") + " event #" + q.callID + " (type " + q.type + ") from " + q.from + " to " + q.to);
                switch (true) {
                    case q.isIncoming():
                        if (q.to === j) {
                            d.post(m.dialIn, {
                                phone: q.from
                            })
                        }
                        break;
                    case q.isHistory():
                        if (q.to === j || q.from === j) {
                            d.post(m.logCall, {
                                event: {
                                    from: q.from,
                                    to: q.to,
                                    start: q.start,
                                    end: q.end,
                                    duration: q.duration,
                                    direction: q.direction,
                                    id: q.callID,
                                    record: q.record
                                }
                            })
                        }
                        break;
                    default:
                        break
                }
            })
        };
        this.isCallAvailable = function() {
            return h.isConnected()
        };
        this.makeCall = function(o, p, q) {
            o = n(o);
            if (!o.length) {
                showErrorNotification("<p>" + _translate("alert.invalid_phone_number") + "</p>");
                q()
            }
            if (!h.isConnected()) {
                i("connection lost, reconnecting...");
                this.connect(function() {
                    i("make call to " + o);
                    h.call(o);
                    q()
                })
            } else {
                i("make call to " + o);
                h.call(o);
                q()
            }
        }
    };
    b = function() {
        var p = new Onpbx();
        var t, n, i, o = {},
            h = true,
            g, k = false,
            j = {},
            m, q;

        function l(u) {
            if (h && typeof console !== "undefined" && typeof console.log !== "undefined") {
                console.log("OnlinePbxTelephony: " + u)
            }
        }

        function s() {
            var u = localStorage.getItem("onlinePbxinputCalls");
            if (u) {
                u = JSON.parse(u)
            } else {
                u = {}
            }
            return u
        }

        function r(u) {
            var v = u.toString().charAt(0);
            u = u.toString().replace(/\D/g, "");
            if (v == "+") {
                u = "+" + u
            }
            return u
        }
        this.connect = function(u) {
            if (p.connected) {
                return
            }
            p.on("connect", function() {
                l("connected");
                p.command("subscribe", {
                    events: {
                        calls: true,
                        blf: false
                    }
                });
                p.on("channel_destroy", function(v) {
                    var w = s();
                    if (w && typeof w[v.uuid] !== "undefined") {
                        k = false;
                        delete w[v.uuid];
                        localStorage.setItem("onlinePbxinputCalls", JSON.stringify(w));
                        localStorage.setItem("haveInbound", false)
                    }
                });
                p.on("channel_answer", function(w) {
                    var x = s();
                    if (x && typeof x[w.uuid] !== "undefined") {
                        var v = x[w.uuid];
                        if (v.destination_number === i && a.isLastActive()) {
                            d.post(o.dialIn, {
                                phone: v.caller_number,
                                external_phone: v.external_phone
                            })
                        }
                        delete x[w.uuid];
                        localStorage.setItem("onlinePbxinputCalls", JSON.stringify(x))
                    }
                });
                p.on("channel_create", function(x) {
                    var v = x.caller_number;
                    v = v.replace("%2B7", "+7");
                    x.caller_number = v;
                    if (x.direction == "inbound") {
                        k = true;
                        localStorage.setItem("haveInbound", true)
                    }
                    var w = localStorage.getItem("haveInbound");
                    if (JSON.parse(w) === true) {
                        k = true
                    }
                    if (x.gate && x.caller_number) {
                        j[x.caller_number] = x.gate
                    }
                    if (k && x.caller_number && x.caller_number.match(/^\+?\d+$/)) {
                        if (x.destination_number && x.destination_number === i) {
                            m = "";
                            if (typeof j[x.caller_number] !== "undefined") {
                                m = j[x.caller_number]
                            }
                            if (x.gate) {
                                m = x.gate
                            }
                            localStorage.setItem("haveInbound", false);
                            if (!q) {
                                if (a.isLastActive()) {
                                    d.post(o.dialIn, {
                                        phone: x.caller_number,
                                        external_phone: m
                                    })
                                }
                            } else {
                                var y = s();
                                x.external_phone = m;
                                y[x.uuid] = x;
                                localStorage.setItem("onlinePbxinputCalls", JSON.stringify(y))
                            }
                        }
                    }
                });
                if (typeof u !== "undefined") {
                    u()
                }
            });
            p.on("disconnect", function() {
                l("disconnect");
                this.connect(u)
            });
            p.connect({
                domain: t,
                key: n
            })
        };
        this.init = function(u, v) {
            o.dialIn = u.urls.dialIn;
            t = u.host;
            n = u.apiKey;
            i = u.managerCode;
            g = u.externalPhones;
            q = u.popupsOnAnswer;
            this.connect(v)
        };
        this.isCallAvailable = function() {
            if (p.connected && i) {
                return true
            }
            return false
        };
        this.makeCall = function(v, w, x) {
            v = r(v);
            k = false;
            if (!v.length) {
                showErrorNotification("<p>" + _translate("alert.invalid_phone_number") + "</p>");
                x()
            }
            var u = {
                from: i,
                to: v
            };
            if (w && typeof g[w] !== "undefined") {
                u.gate_from = g[w];
                u.gate_to = g[w]
            }
            if (!p.connected) {
                l("connection lost, reconnecting...");
                this.connect(function() {
                    l("make call to " + v);
                    p.command("make_call", u, function(y) {
                        l("make call to " + v);
                        x()
                    })
                })
            } else {
                l("make call to " + v);
                p.command("make_call", u, function(y) {
                    l("make call to " + v);
                    x()
                })
            }
        }
    };
    crmTelephonyOptions.services = {
        serverTelephony: function() {
            return new e()
        },
        prostieZvonki: function() {
            return new c()
        },
        onlinepbx: function() {
            return new b()
        },
        customApi: function() {
            return new e()
        }
    }
}(jQuery, tabWatcher));

function addTelephoneIcon() {
    if (!crmTelephonyOptions) {
        return
    }
    if (!crmTelephonyOptions.enabled) {
        return
    }
    $("span.with-phone-icon").each(function() {
        if (!crmTelephony.isCallAvailable()) {
            return
        }
        if (!$(this).text().length) {
            return
        }
        if ($(this).next(".phone-icon.make-call").length) {
            return
        }
        var a = $('<div class="btn btn-white phone-icon make-call no-tr-link"><i class="phone-icon__icon"></i></div>').attr("data-phone", $.trim($(this).text()));
        var b = $.trim($(this).attr("data-site"));
        if (b) {
            a.attr("data-site", b)
        }
        $(this).after(a)
    })
}
$(document).ready(function() {
    if (!crmTelephonyOptions.enabled) {
        return
    }
    crmTelephonyOptions.inited = false;
    crmTelephony = crmTelephonyOptions.services[crmTelephonyOptions.service]();
    crmTelephony.init(crmTelephonyOptions.params, function() {
        if (crmTelephonyOptions.inited) {
            return
        }
        createHistoryLink();
        addTelephoneIcon();
        $(document).delegate(".make-call", "click", function(d) {
            var b = $(this);
            if (b.hasClass("disabled")) {
                return
            }
            b.addClass("disabled");
            var a = $.trim(b.attr("data-phone"));
            var c = $.trim(b.attr("data-site"));
            if (!a.length) {
                a = $.trim(b.next("input").val());
                c = $.trim(b.next("input").attr("data-site"))
            }
            if (!a.length) {
                return
            }
            startBlinking(b);
            crmTelephony.makeCall(a, c, function() {
                b.removeClass("disabled");
                stopBlinking(b)
            })
        });
        $(document).delegate("#add-additional-phone", "click", function() {
            createHistoryLink()
        });
        $(document).delegate(".phone-history-link", "click", function() {
            var c = [];
            var a = $(".input-group .input-field.with-phone-icon");
            a.each(function() {
                var e = $(this).val();
                if (e) {
                    c.push(e)
                }
            });
            if (c.length == 0) {
                showErrorNotification("<p>" + _translate("alert.phones_must_be_set") + "</p>");
                return false
            }
            var d = $(".stat-box-popup.call-history");
            if (d.length == 0) {
                d = $("<div>").addClass("stat-box-popup order call-history").css("display", "none");
                d.html("<h2>" + _translate("info.call_history") + '</h2><a href="#close" class="close"><i></i></a><div class="stat-content"></div>');
                $("footer").after(d)
            }
            var b = crmTelephonyOptions.params.urls.phoneCallHistory;
            if (a.data("history-link")) {
                b = a.data("history-link")
            }
            d.intaroPopup({
                url: b,
                data: {
                    phones: c.slice(0, 30)
                }
            });
            return false
        });
        wtcnct = function(a) {
            if (!crmTelephonyOptions.inited) {
                return
            }
            if ($(this).data("prevType") !== a.type) {
                $(this).data("prevType", a.type);
                switch (a.type) {
                    case "focus":
                        crmTelephony.connect();
                        break
                }
            }
        };
        $(window).blur(wtcnct).focus(wtcnct);
        crmTelephonyOptions.inited = true
    });
    $("#telephony-personal-account").click(function(b) {
        b.preventDefault();
        var a = $(this);
        doPostRequest(a.data("url"), {
            clientId: a.data("client-id")
        })
    })
});
var startBlinking = function(a) {
    if ("undefined" === typeof a) {
        return
    }
    if (a.hasClass(".blink-animation")) {
        return
    }
    a.addClass("blink-animation")
};
var stopBlinking = function(a) {
    a.children().one("animationend webkitAnimationEnd oAnimationEnd MSAnimationEnd", function() {
        a.removeClass("blink-animation")
    })
};
doPostRequest = function(a, d) {
    var b = $('<form action="' + a + '" method="post"/>');
    for (var c in d) {
        b.append($('<input type="hidden" name="' + c + '" value="' + d[c] + '">'))
    }
    $("body").append(b);
    b.submit()
};
var createHistoryLink = function() {
    var d = $(".input-group .input-field.with-phone-icon:visible");
    var b = false;
    d.each(function() {
        var e = $(this);
        var f = $.trim(e.val());
        if (!f.length) {
            return
        }
        if (crmTelephony.isCallAvailable()) {
            var g = $('<div class="btn btn-white phone-icon make-call"><i class="phone-icon__icon"></i></div>');
            if ($(this).val()) {
                g.attr("data-phone", $.trim($(this).val()));
                var h = $.trim($(this).attr("data-site"));
                if (h) {
                    g.attr("data-site", h)
                }
            }
            if (!e.prev().is(".phone-icon")) {
                e.before(g)
            }
        }
        b = true
    });
    if (b) {
        var c;
        var a = $("<a>").addClass("phone-history-link controls").attr("href", "#customer-call-history").html("<span>" + _translate("info.call_history") + "</span>");
        $(".phone-history-link").remove();
        if (d.parents(".order-main-box").length) {
            c = d.last()
        } else {
            c = d.first()
        }
        c.closest(".input-group").append(a).addClass("input-group_with-history")
    }
};
var IntegrationDelivery = (function() {
    var integrationDelivery = {};
    var integrationFormAjax = null;
    integrationDelivery.prefix = "#intaro_crmbundle_ordertype_integrationDeliveryData_";
    integrationDelivery.modules = {};
    integrationDelivery.requiredFields = [];
    integrationDelivery.popupRequiredFields;
    var disableDeliveryType = function() {
        var $deliveryType = $("#intaro_crmbundle_ordertype_deliveryType");
        if (!$deliveryType.length) {
            return
        }
        var map = $deliveryType.data("integration-mapping");
        var val = $deliveryType.val();
        if (!val) {
            return
        }
        if ("undefined" !== map[val]) {
            var $block = $('.integration-delivery[data-code="' + map[val] + '"]');
            if ($block.data("is-created") === true) {
                $deliveryType.find("option:not(:selected)").attr("disabled", "disabled");
                $deliveryType.trigger("chosen:updated")
            }
        }
    };
    changeDeliveryTypeHandler = function() {
        if (integrationDelivery.integrationFormAjax != null) {
            integrationDelivery.integrationFormAjax.abort();
            integrationDelivery.integrationFormAjax = null
        }
        integrationDelivery.popupRequiredFields = undefined;
        var loader = '<span class="small-static-loader"></span>';
        $("#intaro_crmbundle_ordertype_deliveryType").parent().find(".small-static-loader").remove();
        $("#intaro_crmbundle_ordertype_deliveryType").parent().append(loader);
        $("#intaro_crmbundle_ordertype_deliveryType").trigger("before_change");
        var deliveryTypeId = $("#intaro_crmbundle_ordertype_deliveryType").val();
        var orderId = null;
        var matchArr = location.pathname.match(/\/orders\/([^\/]+)\/edit$/);
        if (Array.isArray(matchArr)) {
            orderId = matchArr[1]
        }
        toggleDeliveryCostType();
        integrationDelivery.updateRequiredFields();
        integrationDelivery.requiredFields = [];
        hideFields(false);
        Order.triggerEvent("shipmentStore:available");
        $(".integration-delivery").next(".hide").remove();
        $(".integration-delivery").remove();
        var url = $(this).data("url") + "?delivery_type_id=" + deliveryTypeId;
        if (orderId) {
            url += "&id=" + orderId
        }
        integrationDelivery.integrationFormAjax = $.get(url).success(function(data) {
            $("#delivery-vat-info").remove();
            $("#intaro_crmbundle_ordertype_deliveryType").parent().next().after(data);
            toggleDeliveryCostType();
            hideFields(true);
            toggleDimensionBlock();
            Order.triggerEvent("shipmentStore:available");
            var $activeField = $("#intaro_crmbundle_ordertype_integrationDeliveryData_locked");
            if ($activeField.length) {
                $activeField.change(function() {
                    if (!$(this).is(":checked")) {
                        toggleInputRequired($("#intaro-crm-deliveries"));
                        integrationDelivery.updateRequiredFields(true)
                    } else {
                        integrationDelivery.updateRequiredFields();
                        toggleInputRequired($("#intaro-crm-deliveries"), false)
                    }
                }).change()
            } else {
                integrationDelivery.updateRequiredFields(true)
            }
            if ($("#intaro-crm-deliveries").data("code") in integrationDelivery.modules) {
                integrationDelivery.modules[$("#intaro-crm-deliveries").data("code")]()
            }
            $("#intaro-crm-deliveries").initJsControls();
            var vatRate = $("#delivery-vat-info").data("vat-rate");
            $("#delivery-vat-rate").data("vat-rate", (vatRate + "")).html(formatVatRate(vatRate));
            $("#delivery-cost").change()
        }).always(function(data) {
            $("#intaro_crmbundle_ordertype_deliveryType").parent().find(".small-static-loader").remove();
            integrationDelivery.integrationFormAjax = null
        });
        $("#delivery-address-form").show();
        var mapping = $(this).data("integration-mapping");
        var value = $(this).val()
    };
    toggleDimensionBlock = function() {
        $block = $("#dimension-block");
        var primaryFlag = ($block.data("show-dimension-block") == "1");
        if (primaryFlag) {
            $block.addClass("bk").removeClass("hide")
        } else {
            var currentFlag = false;
            var $integrationBlock = $(".integration-delivery[data-show-dimension-block]");
            if ($integrationBlock.length) {
                currentFlag = ($integrationBlock.data("show-dimension-block") == "1")
            }
            if (currentFlag) {
                $block.removeClass("hide")
            } else {
                $block.addClass("hide")
            }
        }
        toggleBkTopBorder()
    };
    toggleBkTopBorder = function() {
        $(".order-compos .m-box .bk:visible").removeClass("no-top-border").first().addClass("no-top-border")
    };
    toggleDeliveryCostType = function() {
        var $deliveryContainer = $(".integration-delivery");
        if ($deliveryContainer.length) {
            if ($deliveryContainer.data("delivery-net-cost") == "auto" || $deliveryContainer.data("delivery-net-cost") == "manual") {
                var type = $deliveryContainer.data("delivery-net-cost");
                if (type == "auto") {
                    $("#order-delivery-net-cost__link-cost-auto").trigger("click", {
                        recalculate: false
                    })
                } else {
                    $("#order-delivery-net-cost__link-cost-manual").click();
                    $("#order-delivery-net-cost__link-cost-auto").addClass("hide")
                }
            } else {
                var $checkField = $("#intaro_crmbundle_ordertype_deliveryNetCostIsManual");
                if ($checkField.val() == true) {
                    $("#order-delivery-net-cost__link-cost-auto").removeClass("hide")
                } else {
                    $("#order-delivery-net-cost__link-cost-manual").removeClass("hide")
                }
            }
        }
    };
    hideFields = function(hide) {
        var $deliveryForm = $("#intaro-crm-deliveries");
        if ($deliveryForm.length && $deliveryForm.data("hidden-fields") !== undefined) {
            var hidden = $deliveryForm.data("hidden-fields").split(",");
            for (var ind in hidden) {
                if (hide) {
                    $("#" + hidden[ind]).hide()
                } else {
                    $("#" + hidden[ind]).show()
                }
            }
        }
        if ($deliveryForm.length && $deliveryForm.data("show-fields") !== undefined) {
            var hidden = $deliveryForm.data("show-fields").split(",");
            for (var ind in hidden) {
                if (!hide) {
                    $("#" + hidden[ind]).hide()
                } else {
                    $("#" + hidden[ind]).show()
                }
            }
        } else {
            $("#delivery-address-form").data("addressForm").refreshVisibility()
        }
        if (typeof ORDER_OPTIONS != "undefined" && ORDER_OPTIONS.parseAddress != 1 && $("#address-text textarea").length && $("#address-text textarea").val().length) {
            $("#address-text").show()
        }
    };
    integrationDelivery.isCashPaymentOrder = function(isCashTypeIdFunc) {
        if (typeof isCashTypeIdFunc !== "function") {
            throw new Error("Wrong argument")
        }
        var p = $("#payments .payment").map(function() {
            var t = $(this);
            if (t.find("select[id$=status] option:selected").hasClass("payment-complete")) {
                return null
            }
            return t.data("type-id")
        }).filter(function() {
            var v = parseInt(this);
            return v && isCashTypeIdFunc(v)
        });
        if (p.length === 1) {
            return p[0]
        }
        return false
    };
    integrationDelivery.saveFields = function() {
        var $inputs = $('input[id ^= "intaro_crmbundle_ordertype_integrationDeliveryData_"]');
        var data = {};
        $inputs.each(function() {
            if ($(this).val() != "") {
                $(".integration-delivery").data("#" + $(this).attr("id") + "_prev", $(this).val())
            }
        })
    };
    integrationDelivery.updateRequiredFields = function(required, baseSelector) {
        var $lockedField = $("#intaro_crmbundle_ordertype_integrationDeliveryData_locked");
        if ($lockedField.length && $lockedField.is(":checked")) {
            required = undefined
        }
        var $visible = $(".integration-delivery");
        if ($visible.length) {
            var requireds = $visible.attr("data-required-fields");
            if (requireds) {
                requireds = requireds.split(",").concat(integrationDelivery.requiredFields);
                for (var ind in requireds) {
                    if (!requireds[ind]) {
                        continue
                    }
                    var selector = requireds[ind];
                    if (selector[0] != "#") {
                        selector = "#" + selector
                    }
                    if ("undefined" !== typeof baseSelector) {
                        selector = baseSelector + " " + selector
                    }
                    if (required) {
                        selector += ":visible"
                    }
                    var $element = $(selector);
                    if ($element.length) {
                        toggleInputRequired($element, required)
                    }
                }
            }
        }
    };
    integrationDelivery.validateDeliveriesInfo = function($link, ids) {
        $(".script-error").remove();
        var hasError = false;
        for (var id in ids) {
            var $input = $("#" + id);
            var $errorsList = $('<ul class="msg-error script-error"></ul>');
            if (!$input.length || !$input.is(":visible")) {
                continue
            }
            var val = $input.val();
            if (!val) {
                hasError = true;
                if (!$input.parent().find(".msg-error").length) {
                    $input.parent().append($errorsList)
                }
                var message = _translate("message.need_set_" + ids[id]);
                if (message == "message.need_set_" + ids[id]) {
                    message = ids[id]
                }
                var $div = $('<li class="msg-error__item"></li>');
                $div.attr("id", "script_error_" + id).text(message);
                $input.parent().find(".script-error").prepend($div);
                $input.focus().one("input", function() {
                    var $errorItem = $("#script_error_" + $(this).attr("id"));
                    if ($errorItem.siblings().length == 0) {
                        $errorItem.parent(".script-error").remove()
                    } else {
                        $($errorItem).remove()
                    }
                })
            }
        }
        return !hasError
    };
    integrationDelivery.dataIsChange = function(param) {
        if ("undefined" === typeof param) {
            var attr = $("#intaro-crm-deliveries").attr("data-is-change");
            if ("undefined" === typeof attr) {
                return false
            }
            return true
        }
        if (!!param) {
            $("#intaro-crm-deliveries").attr("data-is-change", "true")
        } else {
            $("#intaro-crm-deliveries").removeAttr("data-is-change")
        }
    };
    integrationDelivery.initItemsDeclaredValuePopup = function() {
        var calculateDeclaredSumm = function($popup) {
            var summ = 0;
            $popup.find("table tr:not(:first)").each(function() {
                var isNullByDefault = $("#specify-declared-value").data("is-null");
                if ($(this).find("input").val() == "") {
                    if (isNullByDefault == undefined || !isNullByDefault) {
                        summ += parseFloat($(this).attr("data-price")) * parseFloat($(this).find("td.quantity").text().replace(/,/, "."))
                    }
                    return
                }
                summ += parseFloat($(this).find("input").val().replace(/,/, ".")) * parseFloat($(this).find("td.quantity").text().replace(/,/, "."))
            });
            if (summ > 0) {
                $("#specify-declared-value span").html("(" + priceFormat(summ) + ")")
            } else {
                $("#specify-declared-value span").html("(" + priceFormat(0) + ")")
            }
            return summ
        };
        var buildProductsTable = function($popup) {
            $popup.find("table tr:not(:first)").remove();
            $popup.find(".buttons-bar .msg-warning").addClass("hide");
            var itemsDeclaredValue = $(integrationDelivery.prefix + "itemsDeclaredValue").val();
            try {
                itemsDeclaredValue = JSON.parse(itemsDeclaredValue)
            } catch (er) {
                itemsDeclaredValue = {}
            }
            var $orderProductRows = $("#order-products-table tr.order-product:not(.returned)");
            $orderProductRows.each(function() {
                if ($(this).find("td.status span.product-status > a").hasClass("cancel")) {
                    return
                }
                var $offer = $(this).find('input[name $= "[offer]"]');
                if (!$offer.length || $offer.val() == "") {
                    return
                }
                var name;
                if ($(this).find("td.title > a:first").length) {
                    name = $(this).find("td.title > a:first").text()
                } else {
                    name = $(this).find("td.title > span.tr-link").text()
                }
                var quantity = parseFloatText($(this).find('input[name $= "[quantity]"]').val());
                var price = parseFloatText($(this).find("td.subtotal .value").text());
                price = price / quantity;
                var properties = $("<td></td>").addClass("td-properties").append($(this).find("td.properties-td .additional.elipsis").clone().removeClass("edit"));
                var $row = $("<tr></tr>").attr("data-offer-id", $offer.val()).attr("data-price", price).append('<td class="td-name">' + name + "</td>").append(properties).append('<td class="td-min align-r quantity">' + quantity + "</td>").append('<td class="td-min align-r">' + priceFormat(price) + "</td>").append('<td class="td-input align-r"><input type="text" class="input-field input-field_declared ml-auto"/></td>');
                if (itemsDeclaredValue[$offer.val()] != undefined) {
                    $row.find("input").val(itemsDeclaredValue[$offer.val()])
                }
                $popup.find("table tbody").append($row)
            })
        };
        var openItemsDeclaredValuePopup = function() {
            var $popup = $("#order-product-declared-values-popup");
            $popup.intaroPopup();
            buildProductsTable($popup);
            $popup.find("a.save-button").one("click", function(e) {
                e.preventDefault();
                calculateDeclaredSumm($popup);
                var itemsDeclaredValue = {};
                $popup.find("table tr:not(:first)").each(function() {
                    if ($(this).find("input").val() == "") {
                        return
                    }
                    itemsDeclaredValue[$(this).attr("data-offer-id")] = $(this).find("input").val()
                });
                $(integrationDelivery.prefix + "itemsDeclaredValue").val(JSON.stringify(itemsDeclaredValue)).change();
                $popup.find("a.close").click()
            })
        };
        var $popup = $("#order-product-declared-values-popup");
        buildProductsTable($popup);
        calculateDeclaredSumm($popup);
        $("#order-product-summ").live("totalProductSummUpdated", function() {
            buildProductsTable($popup);
            calculateDeclaredSumm($popup)
        });
        $("#specify-declared-value").click(function(e) {
            e.preventDefault();
            openItemsDeclaredValuePopup()
        })
    };
    integrationDelivery.openDeliveriesPopup = function(link, onAfterPopupOpen) {
        var buildFilter = function() {
            var values = [];
            var $filter = $("#popup-name-filter");
            $popup.find("tr:gt(0)").each(function() {
                var value = $(this).attr("data-name");
                if (values.indexOf(value) < 0) {
                    values.push(value)
                }
            });
            values.sort();
            for (var ind in values) {
                var $option = $('<option value="' + values[ind] + '">' + values[ind] + "</option>");
                $filter.append($option);
                $option.trigger("chosen:updated")
            }
        };
        var ids = {};
        if (integrationDelivery.popupRequiredFields != undefined) {
            ids = integrationDelivery.popupRequiredFields()
        }
        var prefix = "#intaro_crmbundle_ordertype_integrationDeliveryData_";
        if (!IntegrationDelivery.validateDeliveriesInfo(link, ids)) {
            return
        }
        var $popup = $("#available-deliveries");
        var $mainForm = link.parents("form");
        var url = $popup.data("url");
        if ($popup.data("saved-content") && !IntegrationDelivery.dataIsChange(undefined)) {
            $popup.intaroPopup();
            $popup.intaroPopup("setContent", $popup.data("saved-content"));
            buildFilter();
            if (onAfterPopupOpen != undefined) {
                onAfterPopupOpen($popup)
            }
            var $table = $popup.find("table");
            $table.find("tr.important-auto-data").insertAfter($table.find("tr:first"));
            return
        }
        var formData = $mainForm.serializeArray();
        if ("orderId" in ORDER_OPTIONS) {
            formData.push({
                name: "orderId",
                value: ORDER_OPTIONS.orderId
            })
        }
        $popup.intaroPopup();
        $popup.intaroPopup("updateContent", {
            url: url,
            type: "POST",
            data: formData,
            onSuccess: function(response) {
                if (!$(response).hasClass("msg-error")) {
                    IntegrationDelivery.dataIsChange(false);
                    $popup.data("saved-content", response);
                    buildFilter();
                    if (onAfterPopupOpen != undefined) {
                        onAfterPopupOpen($popup)
                    }
                    var $table = $popup.find("table");
                    $table.find("tr.important-auto-data").insertAfter($table.find("tr:first"))
                } else {
                    $popup.data("saved-content", false)
                }
            },
            onError: function(err) {
                $popup.data("saved-content", false);
                alert(_translate("alert.delivery_list_load_error"))
            }
        });
        return false
    };
    integrationDelivery.deliveriesPopupFilter = function() {
        $("#available-deliveries").delegate("#popup-type-filter, #popup-name-filter", "change", function() {
            var $popup = $("#available-deliveries");
            var $rows = $popup.find("tr:gt(0)");
            var attr;
            $rows.show();
            $(".stat-content").css("height", "");
            var type = $("#popup-type-filter").val();
            var name = $("#popup-name-filter").val();
            if (type) {
                $rows.filter(':not([data-type="' + type + '"])').hide()
            }
            if (name) {
                $rows.filter(':not([data-name="' + name + '"])').hide()
            }
        })
    };
    integrationDelivery.toggleCashPayment = function(cashMap, disable) {
        var deliveryId = $("#intaro_crmbundle_ordertype_deliveryType option:selected").val();
        for (var paymentTypeId in cashMap) {
            if (!disable) {
                if (typeof ORDER_OPTIONS.deliveryDefaults[deliveryId] !== "undefined" && ORDER_OPTIONS.deliveryDefaults[deliveryId]["paymentTypes"].indexOf(parseInt(paymentTypeId)) == -1) {
                    continue
                }
            }
            if (disable && !cashMap[paymentTypeId]) {
                continue
            }
            $('.add-payment-type li[data-type-id="' + paymentTypeId + '"]').toggleClass("disabled", !!disable)
        }
    };
    integrationDelivery.toggleAddresVisibility = function(hide, exeptCity) {
        if (exeptCity == undefined) {
            exeptCity = false
        }
        if (hide) {
            toggleInputRequired($("#delivery-address-form"), false);
            if (exeptCity) {
                $('#delivery-address-form > div[id != "region-text"][id != "city-text"]').hide()
            } else {
                $("#delivery-address-form > div").hide()
            }
        } else {
            var $fullField = $("#delivery-address-form").find('input[type="hidden"][name $= "[full]"]');
            var isFull = $fullField.val();
            $fullField.val("1");
            toggleInputRequired($("#delivery-address-form"));
            $("#delivery-address-form > div").show();
            $("#delivery-address-form").data("addressForm").refreshVisibility(true);
            hideFields(true);
            integrationDelivery.updateRequiredFields(true, "#delivery-address-form");
            $fullField.val(isFull)
        }
    };
    integrationDelivery.isLocked = function() {
        if ($("#intaro_crmbundle_ordertype_integrationDeliveryData_locked").length) {
            return $("#intaro_crmbundle_ordertype_integrationDeliveryData_locked").is(":checked")
        } else {
            return false
        }
    };
    integrationDelivery.init = function() {
        if (typeof ORDER_OPTIONS != "undefined" && ORDER_OPTIONS.deliveryActive == 0) {
            return
        }
        if (!$('form[name = "intaro_crmbundle_ordertype"]').length) {
            return
        }
        disableDeliveryType();
        Order.addEventHandler("shipmentStore:available", function() {
            if (!$("#intaro_crmbundle_ordertype_shipmentStore").length) {
                return
            }
            var $deliveryCntr = $(".integration-delivery");
            if ($deliveryCntr.length && $deliveryCntr.data("available-stores") !== undefined) {
                var stores = eval($deliveryCntr.data("available-stores"));
                $("#intaro_crmbundle_ordertype_shipmentStore option").each(function() {
                    var option = $(this);
                    var val = option.val();
                    if (val && stores.indexOf(parseInt(val)) == -1) {
                        option.attr("disabled", "disabled");
                        option.trigger("chosen:updated")
                    }
                });
                var selected = $("#intaro_crmbundle_ordertype_shipmentStore option:selected");
                if (selected.val() && stores.indexOf(parseInt(selected.val())) === -1) {
                    var delivery = $("#intaro_crmbundle_ordertype_deliveryType option:selected").text();
                    alert(_translate("alert.invalid_shipment_store_for_delivery", {
                        shipmentStore: selected.text(),
                        delivery: delivery
                    }));
                    selected.removeAttr("selected");
                    selected.trigger("chosen:updated")
                }
            }
        });
        var $deliveryType = $("#intaro_crmbundle_ordertype_deliveryType");
        if ($deliveryType.length) {
            $deliveryType.change(changeDeliveryTypeHandler)
        }
        toggleDeliveryCostType();
        toggleDimensionBlock();
        var $activeField = $("#intaro_crmbundle_ordertype_integrationDeliveryData_locked");
        if ($activeField.length) {
            $activeField.change(function() {
                if (!$(this).is(":checked")) {
                    toggleInputRequired($("#intaro-crm-deliveries"));
                    integrationDelivery.updateRequiredFields(true)
                } else {
                    integrationDelivery.updateRequiredFields();
                    toggleInputRequired($("#intaro-crm-deliveries"), false)
                }
            }).change()
        } else {
            integrationDelivery.updateRequiredFields(true)
        }
        hideFields(true);
        Order.triggerEvent("shipmentStore:available");
        $("#parse-address-btn").live("addressParsed", function() {
            if ($("#delivery-address-parse-error").text() !== "") {
                $("#address-text").show()
            } else {
                hideFields(true)
            }
        });
        if ($deliveryType.length && $(".order-main-box form").hasClass("is-new") && $deliveryType.val()) {
            $deliveryType.change()
        } else {
            if ($("#intaro-crm-deliveries").data("code") in integrationDelivery.modules) {
                integrationDelivery.modules[$("#intaro-crm-deliveries").data("code")]()
            }
        }
    };
    return integrationDelivery
})();

function DeliveryPackages(a, b) {
    this.$link = a;
    this.$popup = b;
    this.count = 0;
    this.init = function() {
        var c = this;
        this.$popup.data("deliveryPackage", this);
        this.$link.click(function(d) {
            d.preventDefault();
            c.$popup.intaroPopup()
        });
        this.count = this.$popup.find(".parcels__list .parcels__item").length;
        $("#deliveries-parcels-edit").text(_transchoice(this.count, "link.packages", {
            "%count%": this.count
        }));
        this.$popup.find(".status-bar .add-field_type-2 a").click(function(d) {
            d.preventDefault();
            addCollectionFormRow(c.$popup.find(".parcels__list"));
            c.updatePackageList()
        });
        this.$popup.find(".parcels__list").delegate("a.deliveries-remove-parcel", "click", function(d) {
            d.preventDefault();
            $(this).parents(".parcels__item").remove();
            c.updatePackageList(false)
        });
        this.$popup.find(".parcels__list_menu ul").delegate("li", "click", function(g) {
            g.preventDefault();
            var f = c.$popup.find(".parcels__list .parcels__item");
            if ($(this).siblings("li.active").length && !c.validatePackage(f.filter(":not(.hide)"))) {
                return
            }
            $(this).siblings("li").removeClass("active");
            $(this).addClass("active");
            f.addClass("hide");
            f.find('input[name $= "[id]"][value = "' + $(this).attr("data-id") + '"]').parents(".parcels__item").removeClass("hide");
            if (c.$popup.find("table.parcels__table").length) {
                c.$popup.find("table.parcels__table tr td.quantity input").val(0);
                var d = {};
                d = c.$popup.find('.parcels__list .parcels__item:not(.hide) input[name $= "[items]"]').val();
                if (d) {
                    d = JSON.parse(d);
                    for (offerId in d) {
                        c.$popup.find('table.parcels__table tr[data-offerId = "' + offerId + '"] td.quantity input').val(d[offerId])
                    }
                }
            }
        });
        this.$popup.bind("beforeOpen", function() {
            c.$popup.data("data-undo", c.$popup.find(".parcels__list").clone());
            c.updatePackageList();
            c.buildOrderProductList();
            c.$popup.find(".parcels__list_menu ul li:first").click();
            c.$popup.data("saved", false);
            c.validateCount()
        });
        this.$popup.find("table.parcels__table").delegate("tr td.quantity input", "change", function() {
            var d = {};
            c.$popup.find("table.parcels__table tr:not(:first)").each(function() {
                d[$(this).attr("data-offerId")] = $(this).find("td.quantity input").val()
            });
            c.$popup.find('.parcels__list .parcels__item:not(.hide) input[name $= "[items]"]').val(JSON.stringify(d));
            c.validateCount()
        });
        this.$popup.find("a.save-button").click(function(f) {
            f.preventDefault();
            if (c.validateCount().length > 0) {
                alert(c.$popup.data("count-alert"));
                return
            }
            var d = true;
            c.$popup.find(".parcels__list .parcels__item").each(function(h) {
                if (!c.validatePackage($(this))) {
                    d = false;
                    var g = $(this).find('input[name $= "[id]"]').val();
                    c.$popup.find('.parcels__list_menu ul li[data-id = "' + g + '"]').click();
                    return
                }
            });
            if (!d) {
                return
            }
            c.$popup.find(".parcels__list .parcels__item").each(function() {
                var g = $(this).find('input[name $= "[items]"]');
                if (!g.length) {
                    return
                }
                var e = $(this).find('input[name $= "[items]"]').val();
                if (e != "") {
                    e = JSON.parse(e);
                    var h = 0;
                    for (offerId in e) {
                        h += parseFloat(e[offerId])
                    }
                    if (h <= 0) {
                        $(this).remove()
                    }
                } else {
                    $(this).remove()
                }
            });
            c.$popup.data("data-undo", false);
            c.$popup.data("saved", true);
            c.$popup.find("a.close").click();
            c.count = c.$popup.find(".parcels__list .parcels__item").length;
            $("#deliveries-parcels-edit").text(_transchoice(c.count, "link.packages", {
                "%count%": c.count
            }));
            c.$popup.trigger("deliveryPackage.save")
        });
        this.$popup.bind("beforeClose", function() {
            if (c.$popup.data("saved") == false && !confirm(_translate("confirm.close_popup"))) {
                return false
            }
            if (c.$popup.data("data-undo")) {
                c.$popup.find(".parcels__list").html("").append(c.$popup.data("data-undo").children());
                c.$popup.data("data-undo", undefined)
            }
        })
    };
    this.updatePackageList = function(c) {
        if (c == undefined) {
            c = true
        }
        var e = this.$popup.find(".parcels__list_menu ul");
        e.find("li").remove();
        var f = 1;
        var d = this.$popup.find(".parcels__list .parcels__item");
        if (d.length == 0 && c) {
            this.$popup.find(".status-bar .add-field_type-2 a").click()
        }
        d.each(function() {
            $(this).find('input[name $= "[id]"]').val(f);
            $(this).find(".parcels__item_id span").text(f);
            var g = $(e.data("prototype"));
            g.attr("data-id", f);
            g.find("a span").text(g.find("a span").text() + f);
            e.append(g);
            f++
        });
        e.find("li:last").click()
    };
    this.buildOrderProductList = function() {
        var c = this;
        var d = this.$popup.find("table.parcels__table");
        if (d.length == 0) {
            return
        }
        d.find("tr:not(:first)").remove();
        this.$popup.find(".buttons-bar .msg-warning").hide();
        $("#order-products-table tr:not(:first, .returned)").each(function() {
            if ($(this).find('td.title input[name $= "[offer]"]').length == 0) {
                c.$popup.find(".buttons-bar .msg-warning").show();
                return
            }
            var e = $(d.data("prototype"));
            e.attr("data-offerId", $(this).find('td.title input[name $= "[offer]"]').val());
            e.find("td.image").html($(this).find("td.image").html());
            e.find("td.title span.parcels__order-title").html($(this).find("td.title .tr-link")[0].outerHTML);
            var f = e.find("td.title");
            $(this).find("td.properties-td span.additional").each(function() {
                var g;
                for (var h = 0; h < this.childNodes.length; h++) {
                    if (this.childNodes[h].nodeType == Node.TEXT_NODE) {
                        g = this.childNodes[h].textContent.trim();
                        break
                    }
                }
                var j = '<span class="parcels__discr">' + $(this).children("span").text() + ' <span class="parcels__value">' + g + "</span></span>";
                f.append(j)
            });
            e.find("td.article").text($(this).find("td.reference-td").text());
            e.find("td.max-quantity").text($(this).find("td.quantity input").val());
            e.find("td.price-td").html($(this).find("td.subtotal .value").html());
            d.append(e)
        })
    };
    this.validatePackage = function(e) {
        var d = $('<ul class="msg-error script-error"></ul>');
        var f = e.find('input:not([type = "hidden"])');
        f.siblings("ul.script-error").remove();
        var c = true;
        f.each(function() {
            if ($(this).data("not-required")) {
                return
            }
            if (!$(this).val()) {
                c = false;
                if ($(this).siblings("ul.script-error").length) {
                    return
                }
                var g = d.clone();
                var h = $('<li class="msg-error__item"></li>');
                h.text(_translate("message.should_not_be_blank"));
                g.append(h);
                $(this).parent().append(g);
                $(this).focus().one("input", function() {
                    $(this).siblings("ul.script-error").remove()
                })
            }
        });
        return c
    };
    this.validateCount = function() {
        var c = this;
        var e = {};
        var g = this.$popup.find("table.parcels__table");
        if (g.length == 0) {
            return []
        }
        this.$popup.find("table.parcels__table tr:not(:first)").each(function() {
            $(this).removeClass("selected");
            e[$(this).attr("data-offerId")] = 0
        });
        this.$popup.find("table.parcels__table tr td.title span.parcels__order-title i").remove();
        var d = this.$popup.find(".parcels__list .parcels__item");
        if (d.length == 0) {
            return true
        }
        d.each(function() {
            var h = $(this).find('input[name $= "[items]"]').val();
            var i = $(this).find('input[name $= "[id]"]').val();
            if (h != "") {
                h = JSON.parse(h);
                if (h) {
                    for (offerId in e) {
                        if (h[offerId]) {
                            e[offerId] += parseFloat(h[offerId])
                        }
                    }
                    for (offerId in h) {
                        if (h[offerId] > 0) {
                            c.$popup.find('table.parcels__table tr[data-offerId = "' + offerId + '"] td.title span.parcels__order-title').append('<i class="in-order">ֲ&nbsp;׃ןאךמגךו&nbsp;' + i + "</i> ")
                        }
                    }
                }
            }
        });
        var f = [];
        this.$popup.find("table.parcels__table .warning-circle-icon").remove();
        this.$popup.find("table.parcels__table tr:not(:first)").each(function() {
            var h = parseFloat($(this).find("td.max-quantity").text().replace(/,/, "."));
            if (isNaN(h)) {
                return
            }
            if (h == e[$(this).attr("data-offerId")]) {
                $(this).addClass("selected")
            } else {
                f.push($(this).attr("data-offerId"))
            }
            if (h >= e[$(this).attr("data-offerId")]) {
                return
            }
            var i = $(this).find("td.quantity input");
            i.val(i.val() - (e[$(this).attr("data-offerId")] - h)).change();
            i.after('<span class="warning-circle-icon" style="margin-left: 5px;">!</span>')
        });
        return f
    }
};
var IntegrationPayment = (function() {
    var b = {};
    var a = {};
    b.init = function() {
        $.each(a, function() {
            this.init()
        });
        $("#payments .payment").each(function() {
            b.initEl($(this))
        })
    };
    b.initEl = function(i) {
        var f = i.data("integration-code");
        var g = i.find(".integration-payment-wrapper");
        if (!f) {
            g.html("");
            return
        }
        var h = i.data("integrationFormAjax");
        if (h) {
            h.abort();
            i.data("integrationFormAjax", "")
        }
        var k = i.data("id");
        var j = '<span class="small-static-loader"></span>';
        g.show();
        g.find(".small-static-loader").remove();
        g.append(j);
        var e = {};
        if (k) {
            e.paymentId = k
        }
        var d = "crm_integration_payment_data_form_" + f;
        var c = Routing.generate(d, e);
        h = $.get(c).success(function(l) {
            g.html(l)
        }).always(function(l) {
            i.data("integrationFormAjax", "")
        });
        i.data("integrationFormAjax", h)
    };
    b.registerModule = function(d) {
        var c = d.name;
        if (!c || a[c]) {
            throw new Error("Module with this name is already registered")
        }
        a[c] = d
    };
    return b
})();
(function() {
    function a() {
        function c(e) {
            return e.closest(".payment")
        }

        function b(f) {
            var e;
            if (f.hasClass("pay-send-btn")) {
                e = f
            } else {
                e = f.closest("ul").siblings(".pay-send-btn")
            }
            e.addClass("pay-send-btn_load");
            return e
        }
        var d = function(f, e) {
            var g;
            if (f.success) {
                g = e.attr("data-send-message") || e.text()
            } else {
                g = f.message
            }
            e.replaceWith('<span class="pay-send-btn pay-send-btn_disabled">' + g + "</span>").end().closest(".form-element-hint__col").find(".hint-message").remove()
        };
        $(".payment [data-pay-send-sms]").live("click", function(i) {
            i.preventDefault();
            var h = $(this);
            var g = c(h);
            var j = g.data("mwsPaymentAjaxRequest");
            if (j) {
                return
            }
            var f = b($(this));
            j = $.ajax({
                url: Routing.generate("crm_integration_payments_yandex_kassa_send_sms"),
                data: {
                    paymentId: g.data("id"),
                    type: h.data("pay-send-type"),
                    phone: h.data("pay-send-phone")
                },
                type: "POST",
                success: function(e) {
                    d(e, f)
                }
            });
            g.data("mwsPaymentAjaxRequest", j)
        });
        $(".payment [data-pay-send]").live("click", function(j) {
            j.preventDefault();
            var i = $(this);
            var h = c(i);
            var g = h.data("emailPaymentAjaxRequest");
            if (g) {
                return
            }
            var f = b(i);
            g = $.ajax({
                url: Routing.generate("crm_integration_payments_yandex_kassa_send_email"),
                data: {
                    paymentId: h.data("id"),
                    email: i.data("pay-send-email").trim()
                },
                type: "POST",
                success: function(e) {
                    d(e, f)
                }
            });
            h.data("emailPaymentAjaxRequest", g)
        });
        $(".payment .pay-send-btn_link").live("click", function(l) {
            l.preventDefault();
            var j = $(this);
            var i = c(j);
            var h = i.data("linkPaymentAjaxRequest");
            if (h) {
                return
            }
            var m = j.parent();
            var g = m.find("textarea#pay-url");
            g.attr("disabled", "disabled");
            var k = m.find("[data-clipboard-target]");
            k.attr("disabled", "disabled");
            var f = '<span class="small-loader"></span>';
            k.find(".small-static-loader").remove();
            k.append(f);
            m.find(".hint-message").remove();
            k.click(function() {
                j.attr("disabled", "disabled");
                j.text(_translate("button.copied"));
                setTimeout(function() {
                    k.removeAttr("disabled");
                    k.text(_translate("button.copy"))
                }, 10000)
            });
            h = $.ajax({
                url: Routing.generate("crm_integration_payments_yandex_kassa_link_invoice"),
                data: {
                    paymentId: i.data("id")
                },
                type: "POST",
                success: function(e) {
                    var o;
                    o = e.message;
                    if (e.success) {
                        k.removeAttr("disabled");
                        g.removeAttr("disabled");
                        g.text(e.url)
                    }
                    var n = $("<div>").addClass("hint-message").addClass("wo-padding").html(o);
                    k.after(n)
                },
                complete: function() {
                    k.find(".small-loader").remove()
                }
            });
            i.data("linkPaymentAjaxRequest", h)
        })
    }(function() {
        IntegrationPayment.registerModule({
            name: "yandex_kassa",
            init: function() {
                a()
            }
        })
    })()
})();
var IntegrationFiscal = (function() {
    var a = {};
    a.init = function() {
        $("a.fiscal-retry").live("click", function(g) {
            g.preventDefault();
            var f = $(this);
            var c = f.parents(".payment");
            var h = c.find(".payment-fiscal");
            var d = h.data("fiscalRetryAjax");
            if (d) {
                return
            }
            var b = c.data("id");
            d = $.ajax({
                url: Routing.generate("crm_fiscal_atol_online_retry"),
                data: {
                    paymentId: b
                },
                type: "POST",
                beforeSend: function() {
                    f.addClass("pay-send-btn_load")
                },
                success: function(e) {
                    h.replaceWith(e)
                },
                complete: function(e) {
                    h.data("fiscalRetryAjax", "");
                    f.removeClass("pay-send-btn_load")
                }
            });
            h.data("fiscalRetryAjax", d)
        });
        $("a.fiscal-refund").live("click", function(g) {
            g.preventDefault();
            var f = $(this);
            var d = f.parents(".payment");
            var h = d.find(".payment-fiscal");
            var c = h.data("fiscalRefundAjax");
            if (c) {
                return
            }
            if (!confirm(_translate("confirm.fiscal_refund"))) {
                return
            }
            var b = d.data("id");
            c = $.ajax({
                url: Routing.generate("crm_fiscal_atol_online_sell_refund"),
                data: {
                    paymentId: b
                },
                type: "POST",
                beforeSend: function() {
                    f.addClass("pay-send-btn_load")
                },
                success: function(e) {
                    h.replaceWith(e)
                },
                complete: function(e) {
                    h.data("fiscalRefundAjax", "");
                    f.removeClass("pay-send-btn_load")
                }
            });
            h.data("fiscalRefundAjax", c)
        });
        $("a.fiscal-manual").live("click", function(g) {
            g.preventDefault();
            var f = $(this);
            var d = f.parents(".payment");
            var h = d.find(".payment-fiscal");
            var c = h.data("fiscalManualAjax");
            if (c) {
                return
            }
            var b = d.data("id");
            c = $.ajax({
                url: Routing.generate("crm_fiscal_atol_online_sell"),
                data: {
                    paymentId: b
                },
                type: "POST",
                beforeSend: function() {
                    f.addClass("pay-send-btn_load")
                },
                success: function(e) {
                    h.replaceWith(e)
                },
                complete: function(e) {
                    h.data("fiscalManualAjax", "");
                    f.removeClass("pay-send-btn_load")
                }
            });
            h.data("fiscalManualAjax", c)
        })
    };
    return a
})();
IntegrationDelivery.modules.axiomus = function() {
    var e = IntegrationDelivery.prefix;
    $(e + "carryBeginDate," + e + "carryEndDate").datepicker({
        dateFormat: _getDateFormatString(),
        minDate: new Date()
    });
    $("#time-group > label").addClass("required");
    var c = {
        delivery: {
            hide: ["#region-text"],
            required: ["#intaro_crmbundle_ordertype_deliveryDate", "#intaro_crmbundle_ordertype_deliveryAddress_street", "#intaro_crmbundle_ordertype_deliveryAddress_building"]
        },
        carry: {
            hide: ["#delivery-address-form", "#time-group"],
            required: ["#intaro_crmbundle_ordertype_integrationDeliveryData_carryBeginDate", "#intaro_crmbundle_ordertype_integrationDeliveryData_carryEndDate"]
        },
        post: {
            hide: ["#date-text", "#time-group"],
            required: ["#intaro_crmbundle_ordertype_shipmentDate", "#intaro_crmbundle_ordertype_deliveryAddress_index", "#intaro_crmbundle_ordertype_deliveryAddress_region", "#intaro_crmbundle_ordertype_deliveryAddress_city", "#intaro_crmbundle_ordertype_deliveryAddress_street", "#intaro_crmbundle_ordertype_deliveryAddress_building", "#intaro_crmbundle_ordertype_lastName"]
        },
        dpd: {
            hide: ["#date-text", "#time-group"],
            required: ["#intaro_crmbundle_ordertype_shipmentDate", "#intaro_crmbundle_ordertype_lastName", "#intaro_crmbundle_ordertype_deliveryAddress_index", "#intaro_crmbundle_ordertype_deliveryAddress_region", "#intaro_crmbundle_ordertype_deliveryAddress_city", "#intaro_crmbundle_ordertype_deliveryAddress_street", "#intaro_crmbundle_ordertype_deliveryAddress_building"]
        },
        region_courier: {
            hide: ["#city-text", "#region-text"],
            required: ["#intaro_crmbundle_ordertype_deliveryDate", "#intaro_crmbundle_ordertype_deliveryAddress_index", "#intaro_crmbundle_ordertype_deliveryAddress_street", "#intaro_crmbundle_ordertype_deliveryAddress_building", "#intaro_crmbundle_ordertype_lastName"]
        },
        region_pickup: {
            hide: ["#delivery-address-form"],
            required: ["#intaro_crmbundle_ordertype_deliveryDate", "#intaro_crmbundle_ordertype_lastName"]
        },
        boxberry_pickup: {
            hide: ["#delivery-address-form", "#date-text", "#time-group"],
            required: ["#intaro_crmbundle_ordertype_shipmentDate", "#intaro_crmbundle_ordertype_lastName"]
        },
        strizh: {
            hide: [],
            required: ["#intaro_crmbundle_ordertype_lastName", "#intaro_crmbundle_ordertype_deliveryDate", "#intaro_crmbundle_ordertype_deliveryAddress_city", "#intaro_crmbundle_ordertype_deliveryAddress_street"]
        }
    };
    var d = {
        delivery: {},
        carry: {},
        post: {
            cash: {
                valuation: "true",
                post_tarif: "enable"
            },
            valuation: {
                insurance: "enable"
            },
            class1: {
                not_avia: "disable"
            }
        },
        dpd: {
            cash: {
                valuation: "true"
            }
        },
        region_courier: {
            not_open: {
                part_return: "disable"
            }
        },
        region_pickup: {
            not_open: {
                part_return: "disable"
            }
        },
        boxberry_pickup: {
            part_return: {
                checkup: "disable"
            }
        },
        strizh: {}
    };

    function f(h, i) {
        if (h.is("input") || h.is("select")) {
            h = h.parents(".input-group")
        }
        if (i == undefined) {
            i = h.attr("data-hide")
        } else {
            if (h.attr("data-hide") == undefined) {
                h.attr("data-hide", h.hasClass("hide"))
            }
        }
        if (i == undefined) {
            return
        }
        if (i === "true" || i === true) {
            h.addClass("hide");
            h.find("input, select").each(function() {
                toggleInputRequired($(this), false)
            })
        } else {
            h.removeClass("hide");
            if (!IntegrationDelivery.isLocked()) {
                h.find("input, select").each(function() {
                    toggleInputRequired($(this))
                })
            }
        }
    }

    function g(i, h, k) {
        var j = $(e + "deliveryType").val();
        if (h in d[j]) {
            for (var m in d[j][h]) {
                var l = i.find('input[type="checkbox"][value="' + m + '"]');
                if (d[j][h][m] == "disable") {
                    if (k == true) {
                        l.attr("disabled", true).attr("checked", false)
                    } else {
                        l.attr("disabled", false)
                    }
                }
                if (d[j][h][m] == "enable") {
                    if (k == true) {
                        l.attr("disabled", false)
                    } else {
                        l.attr("disabled", true).attr("checked", false)
                    }
                }
                if (d[j][h][m] == "true") {
                    if (k == true) {
                        l.attr("checked", true)
                    }
                }
                if (d[j][h][m] == "false") {
                    if (k == true) {
                        l.attr("checked", false)
                    }
                }
            }
        }
    }
    var a = function() {
        $(e + "carryPickup, " + e + "dpdPickup, " + e + "logiboxPickup, " + e + "regionOfficeCode, " + e + "boxberryOfficeCode").each(function() {
            if ($(this).prev("input:visible").length == 0) {
                return
            }
            if ($(this) && $(this).data("address")) {
                $("#delivery-point-address").text($(this).data("address")).parent().removeClass("hide");
                $(e + "pointAddress").val($(this).data("address"))
            } else {
                $("#delivery-point-address").text("").parent().addClass("hide");
                $(e + "pointAddress").val("")
            }
            if ($(this) && $(this).data("schedule")) {
                $("#delivery-schedules").text($(this).data("schedule")).parent().removeClass("hide");
                $(e + "schedules").val($(this).data("schedule"))
            } else {
                $("#delivery-schedules").text("").parent().addClass("hide");
                $(e + "schedules").val("")
            }
        })
    };
    var b = function() {
        if ($("#intaro-crm-deliveries").data("code") != "axiomus") {
            return
        }
        var i = $('#intaro-crm-deliveries[data-code="axiomus"]').data("cash-map");
        var h = IntegrationDelivery.isCashPaymentOrder(function(j) {
            return i && i[j]
        });
        $('#intaro-crm-deliveries[data-code="axiomus"] .checkbox-list').each(function() {
            g($(this), "cash", h)
        })
    };
    $("#payments").bind("collection:changed", b);
    b();
    $(e + "deliveryType").change(function() {
        var h = $(this).val();
        b();
        f($("#delivery-address-form"));
        if ($(this).attr("data-previous") != undefined && c[$(this).attr("data-previous")] != undefined) {
            for (fieldId in c[$(this).attr("data-previous")]["hide"]) {
                f($(c[$(this).attr("data-previous")]["hide"][fieldId]))
            }
        }
        $(this).attr("data-previous", $(this).val());
        IntegrationDelivery.updateRequiredFields();
        IntegrationDelivery.requiredFields = c[$(this).val()]["required"];
        IntegrationDelivery.updateRequiredFields(true);
        for (fieldId in c[$(this).val()]["hide"]) {
            f($(c[$(this).val()]["hide"][fieldId]), true)
        }
        $("#intaro-crm-deliveries [data-delivery-type]").each(function() {
            var i = true;
            if ($(this).data("delivery-type").indexOf(h) != -1) {
                i = false
            }
            f($(this), i)
        });
        a()
    }).change();
    $(e + "deliveryCity").change(function() {
        if (!$(this).is(":visible") || $(this).val() == "") {
            return
        }
        $("#region-text input").val($(this).find('option[value="' + $(this).val() + '"]').text())
    }).change();
    $(e + "regionCityName").bind("autocompleteselect", function(h, i) {
        if (i.item.regionCode != undefined) {
            $(e + "regionRegionCode").val(i.item.regionCode)
        } else {
            $(e + "regionRegionCode").val("")
        }
    });
    $('#intaro-crm-deliveries[data-code="axiomus"] .checkbox-list input[type="checkbox"]').change(function() {
        g($(this).parent(), $(this).attr("value"), $(this).is(":checked"))
    }).change();
    $(e + "carryPickup, " + e + "dpdPickup, " + e + "regionOfficeCode, " + e + "boxberryOfficeCode").change(function() {
        if ("#" + $(this).attr("id") == e + "dpdPickup" && $(e + "deliveryType").val() == "dpd") {
            if ($(this).val() == "") {
                f($("#delivery-address-form"))
            } else {
                f($("#delivery-address-form"), true)
            }
        }
    }).change();
    $(e + "carryPickup, " + e + "dpdPickup, " + e + "regionOfficeCode, " + e + "boxberryOfficeCode").prev().bind("autocompleteselect", function(h, i) {
        if (i.item.address != undefined && i.item.address != "") {
            $(this).autocomplete("option", "valueField").data("address", i.item.address)
        } else {
            $(this).autocomplete("option", "valueField").data("address", "")
        }
        if (i.item.schedule != undefined && i.item.schedule != "") {
            $(this).autocomplete("option", "valueField").data("schedule", i.item.schedule)
        } else {
            $(this).autocomplete("option", "valueField").data("schedule", "")
        }
        a()
    });
    enableAutocomplete();
    $("#intaro_crmbundle_ordertype_deliveryType").one("before_change", function() {
        $("#time-group > label").removeClass("required");
        $("#payments").unbind("collection:changed");
        var h = $(e + "deliveryType").val();
        for (fieldId in c[h]["hide"]) {
            f($(c[h]["hide"][fieldId]), false)
        }
        for (fieldId in c[h]["required"]) {
            toggleInputRequired($(c[h]["required"][fieldId]))
        }
        f($("#delivery-address-form"))
    })
};
IntegrationDelivery.modules.courier = function() {
    var b = function() {
        var h = $(this),
            g = h.parent(),
            f = h.val(),
            e = $("#intaro_crmbundle_ordertype_deliveryDate").datepicker("getDate"),
            d = h.data("url") + "?courier=" + f,
            c = '<span class="small-static-loader select-loader"></span>';
        $(".control-after", g.parent()).remove();
        if (!f) {
            $(".courier-detail", g.parent()).remove();
            return
        }
        e = $.datepicker.formatDate("yy-mm-dd", e);
        if (!e || isNaN(Date.parse(e))) {
            return
        }
        d += "&date=" + e;
        g.find(".small-static-loader").remove();
        g.append(c);
        $.get(d).success(function(i) {
            $(".control-after", g.parent()).remove();
            g.after(i)
        }).always(function(i) {
            g.find(".small-static-loader").remove()
        })
    };
    var a = $("#intaro_crmbundle_ordertype_integrationDeliveryData_courier");
    a.live("change", b).change();
    $("#intaro_crmbundle_ordertype_deliveryDate").bind("change", function() {
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_courier").trigger("change")
    })
};
IntegrationDelivery.modules.ddelivery = function() {
    var d = IntegrationDelivery.prefix;
    enableAutocomplete();
    IntegrationDelivery.popupRequiredFields = function() {
        return {
            intaro_crmbundle_ordertype_site: "site",
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_weight: "weight",
            intaro_crmbundle_ordertype_length: "length",
            intaro_crmbundle_ordertype_width: "width",
            intaro_crmbundle_ordertype_height: "height",
            intaro_crmbundle_ordertype_paymentType: "payment_type",
            intaro_crmbundle_ordertype_integrationDeliveryData_receiverCity: "delivery_city"
        }
    };

    function b() {
        var e = $(d + "pickuppoint");
        e.autocomplete("option", "source", []);
        e.parent().addClass("loading");
        $.ajax({
            url: e.data("url"),
            data: {
                term: $(d + "receiverCityRef").val()
            },
            success: function(h) {
                e.autocomplete("option", "source", h);
                var g = $(".integration-delivery").data(d + "pickuppointId_prev");
                if (g) {
                    var j = false;
                    for (var f = 0; f < h.length; f++) {
                        if (h[f].value == g) {
                            $(d + "pickuppoint").data("intaroAutocomplete")._trigger("select", "autocompleteselect", {
                                item: h[f]
                            });
                            $(d + "pickuppointId").trigger("change", [h[f]]);
                            j = true;
                            break
                        }
                    }
                    if (!j) {
                        $(d + "pickuppointId").val("")
                    }
                }
            },
            complete: function() {
                e.parent().removeClass("loading")
            }
        })
    }
    IntegrationDelivery.saveFields();
    var c = function() {
        if ($("#intaro-crm-deliveries").data("code") != "ddelivery" || !$("#intaro-crm-deliveries").data("editable")) {
            return
        }
        IntegrationDelivery.saveFields();
        $(d + "pickupType," + d + "deliveryType," + d + "deliveryName," + d + "days," + d + "cost," + d + "returnPrice," + d + "returnClientPrice," + d + "returnPartialPrice," + d + "codFee,").val("");
        $(d + "pickupType").change();
        $("#delivery-type").text(_translate("info.not_set"));
        $("#delivery-days, #integration-delivery-cost, #integration-delivery-days").text("").parent().addClass("hide");
        Order.setDeliveryCost(0);
        Order.setDeliveryNetCost(0)
    };
    if ($(d + "receiverCity").val() != "" && $(d + "receiverCityRef").val() == "") {
        invokeAutocompleteWithTerm($(d + "receiverCity"), $("#delivery-address-form").data("addressForm").getCity(false))
    }
    if ($("#city-text input").val() != "" && $(d + "receiverCity").val() == "") {
        invokeAutocompleteWithTerm($(d + "receiverCity"), $("#delivery-address-form").data("addressForm").getCity(false))
    }
    $(d + "receiverCityRef").change(function() {
        if ($(d + "receiverCity").val() != "") {
            $("#city-text input").val($(d + "receiverCity").val())
        }
        $(d + "pickuppointId").val("").change()
    });
    $(d + "pickupType").change(function() {
        IntegrationDelivery.updateRequiredFields(false);
        if ($(this).val() == 1) {
            b();
            $(d + "pickuppoint").parents(".input-group").show();
            IntegrationDelivery.toggleAddresVisibility(true);
            IntegrationDelivery.requiredFields = [d + "pickuppoint"];
            IntegrationDelivery.updateRequiredFields(true)
        } else {
            $(d + "pickuppoint").parents(".input-group").hide();
            IntegrationDelivery.toggleAddresVisibility(false);
            IntegrationDelivery.requiredFields = ["street-text", "building-text", "flat-text"];
            IntegrationDelivery.updateRequiredFields(true)
        }
    }).change();
    $(d + "pickuppointId").change(function() {
        var f = $(this);
        if ($(this).val() == "") {
            $(d + "pickuppointAddress").val("");
            $("#delivery-point-address").text("").parent().addClass("hide");
            return
        }
        var e = $(d + "pickuppoint").autocomplete("option", "source").find(function(h, g, i) {
            return h.value == f.val()
        });
        if (e == undefined) {
            return
        }
        $(d + "pickuppointAddress").val(e.address);
        $("#delivery-point-address").text(e.address).parent().removeClass("hide")
    });
    var a = function() {
        if ($("#intaro-crm-deliveries").data("code") != "ddelivery") {
            return
        }
        c();
        IntegrationDelivery.dataIsChange(true)
    };
    $("#payments").bind("collection:changed", a);
    $("#intaro_crmbundle_ordertype_weight,        #intaro_crmbundle_ordertype_length,        #intaro_crmbundle_ordertype_width,        #intaro_crmbundle_ordertype_height,        #intaro_crmbundle_ordertype_shipmentStore," + d + "receiverCityRef," + d + "declaredValue").change(a);
    $("#order-product-summ").live("totalProductSummUpdated", function() {
        c();
        IntegrationDelivery.dataIsChange(true)
    });
    $("#intaro_crmbundle_ordertype_deliveryType").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "ddelivery") {
            return
        }
        $("#order-product-summ").die("totalProductSummUpdated");
        $("#intaro_crmbundle_ordertype_weight").unbind("change");
        $("#payments").unbind("collection:changed", a);
        $("#intaro-crm-deliveries .deliveries-popup-open").unbind("click")
    });
    $("#intaro-crm-deliveries .deliveries-popup-open").click(function(g) {
        g.preventDefault();
        var f = function(j) {
            var h = $(".integration-delivery").data(d + "deliveryType_prev");
            var i = $(".integration-delivery").data(d + "pickupType_prev");
            if (!h && !i) {
                return
            }
            var e = "tr";
            if (h) {
                e += '[data-delivery-type = "' + h + '"]'
            }
            if (i) {
                e += '[data-pickup-type = "' + i + '"]'
            }
            j.find(e).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), f)
    });
    IntegrationDelivery.deliveriesPopupFilter();
    $("#available-deliveries").delegate(".modern-table tr td", "click", function(g) {
        c();
        var h = $("#available-deliveries");
        var f = $(this).parent();
        if ($(d + "pickupType").val() != f.attr("data-pickup-type")) {
            $(d + "pickupType").val(f.attr("data-pickup-type")).change()
        }
        $("#delivery-type").text(f.attr("data-delivery-name"));
        $(d + "deliveryType").val(f.attr("data-delivery-type"));
        $(d + "deliveryName").val(f.attr("data-delivery-name"));
        $(d + "days").val(f.attr("data-days"));
        $("#integration-delivery-days").text(f.attr("data-days") + " " + _translate("measure.day.cont")).parent().removeClass("hide");
        $(d + "cost").val(f.attr("data-cost"));
        Order.setDeliveryNetCost(parseFloat(f.attr("data-net-cost")).toFixed(2));
        Order.setDeliveryCost(f.attr("data-cost"));
        $("#integration-delivery-cost").html(priceFormat(f.attr("data-cost"))).parent().removeClass("hide");
        $(d + "returnPrice").val(f.attr("data-return-price"));
        $(d + "returnClientPrice").val(f.attr("data-return-client-price"));
        $(d + "returnPartialPrice").val(f.attr("data-return-partial-price"));
        $(d + "codFee").val(f.attr("data-cod-fee"));
        IntegrationDelivery.saveFields();
        h.find(".close").click()
    })
};
IntegrationDelivery.modules.dellin = function() {
    var b = IntegrationDelivery.prefix;
    enableAutocomplete();
    $(b + "documentDate").datepicker({
        dateFormat: "dd.mm.yy"
    });
    IntegrationDelivery.requiredFields = ["intaro_crmbundle_ordertype_deliveryAddress_street", "intaro_crmbundle_ordertype_deliveryAddress_building"];
    var a = function(e, d) {
        $("#integration-delivery-cost").html("").addClass("hide");
        $(b + "cost").val("");
        $("#integration-delivery-days").text("").parent().addClass("hide");
        $(b + "days").val("");
        if (e) {
            $(b + "receiverTerminal").val("").change();
            $("#dellin-receiver-terminal select option").remove();
            $("#dellin-terminal-warning").removeClass("hide")
        }
        if (d) {
            $(b + "senderTerminal").val("").change();
            $("#dellin-sender-terminal select option").remove();
            $("#dellin-terminal-warning").removeClass("hide")
        }
    };
    var c = function(d) {
        d = d + "0".repeat(25 - d.length);
        if (d.substring(0, 1) == "0") {
            d = d.substring(1)
        }
        return d
    };
    $("#intaro_crmbundle_ordertype_contragent_contragentType").change(function() {
        var d = $(b + "documentType," + b + "documentSerial," + b + "documentNumber," + b + "documentDate").parents(".input-group");
        var e = $(b + "legalForm," + b + "juridicalAddressStreet," + b + "juridicalAddressHouse").parents(".input-group");
        if ($(this).val() == "individual") {
            e.hide();
            toggleInputRequired(e, false);
            d.show();
            toggleInputRequired(e, true)
        } else {
            e.show();
            toggleInputRequired(e, true);
            d.hide();
            toggleInputRequired(e, false)
        }
    }).change();
    $("#dellin-link-calculate").click(function(j) {
        j.preventDefault();
        ids = {
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_weight: "weight",
            intaro_crmbundle_ordertype_length: "length",
            intaro_crmbundle_ordertype_width: "width",
            intaro_crmbundle_ordertype_height: "height"
        };
        if (!IntegrationDelivery.validateDeliveriesInfo($(this), ids)) {
            return
        }
        if ($(b + "receiverCityKladr").val() == "") {
            var g = $("#intaro_crmbundle_ordertype_deliveryAddress_city");
            if (g.val() != "") {
                var h = g.val();
                g.autocomplete("option", "valueField").val("");
                g.val("");
                invokeAutocompleteWithTerm(g, h)
            } else {
                g.parent().append('<ul class="msg-error script-error" id="script_error_delivery_city"><li class="msg-error__item">' + _translate("message.need_set_delivery_city") + "</li></ul>");
                g.focus().one("input", function() {
                    var e = $("#script_error_delivery_city");
                    e.remove()
                })
            }
            return
        }
        var d = '<span class="small-static-loader"></span>';
        $("#dellin-link-calculate").parent().append(d);
        var k = $(this).parents("form");
        var f = $(this).data("url");
        var i = k.serializeArray();
        if ("orderId" in ORDER_OPTIONS) {
            i.push({
                name: "orderId",
                value: ORDER_OPTIONS.orderId
            })
        }
        $.ajax({
            url: f,
            type: "POST",
            data: i,
            success: function(e) {
                if (e.error.error != undefined) {
                    var m = e.error.error;
                    if (e.error.message != undefined) {
                        m += ":" + e.error.message
                    }
                    alert(m);
                    return
                }
                if ($(b + "payerType").val() != "receiver") {
                    Order.setDeliveryNetCost(e.result.price);
                    Order.setDeliveryCost(e.result.price)
                } else {
                    Order.setDeliveryNetCost(0, true)
                }
                $("#integration-delivery-cost").html(priceFormat(e.result.price)).removeClass("hide");
                $(b + "cost").val(e.result.price);
                $("#integration-delivery-days").text(e.result.time.value).parent().removeClass("hide");
                $(b + "days").val(e.result.time.value);
                $("#dellin-sender-terminal select option").remove();
                $("#dellin-receiver-terminal select option").remove();
                $("#dellin-sender-terminal span.message").remove();
                $("#dellin-receiver-terminal span.message").remove();
                for (var l = 0; l < e.result.derival.terminals.length; l++) {
                    $option = $("<option></option>");
                    $option.attr("value", e.result.derival.terminals[l].name + "|" + e.result.derival.terminals[l].address).text(e.result.derival.terminals[l].name);
                    $("#dellin-sender-terminal select").append($option)
                }
                for (var l = 0; l < e.result.arrival.terminals.length; l++) {
                    $option = $("<option></option>");
                    $option.attr("value", e.result.arrival.terminals[l].name + "|" + e.result.arrival.terminals[l].address).text(e.result.arrival.terminals[l].name);
                    $("#dellin-receiver-terminal select").append($option)
                }
                $("#dellin-sender-terminal select, #dellin-receiver-terminal select").removeClass("hide").change();
                $("#dellin-terminal-warning").addClass("hide")
            },
            error: function(e) {
                alert(_translate("alert.delivery_list_load_error"))
            },
            complete: function() {
                $("#dellin-link-calculate").parent().find(".small-static-loader").remove()
            }
        })
    });
    $(b + "juridicalAddressStreet").autocomplete("option", "source", function(f, e) {
        var h = $("#intaro_crmbundle_ordertype_deliveryAddress_cityId").val();
        var d = $(b + "juridicalAddressStreet");
        if (h == "") {
            e([{
                label: "City required",
                value: -1
            }]);
            return
        }
        if (d.data("xhr")) {
            d.data("xhr").abort()
        }
        var g = $.ajax({
            url: Routing.generate("crm_dictionary_geohelper_street"),
            data: {
                filter: {
                    cityId: h,
                    name: f.term
                }
            },
            dataType: "json",
            success: function(i) {
                if (i.result == undefined) {
                    e(i);
                    return
                }
                i = i.result.map(function(k) {
                    if (!k.codes || !k.codes.kladr) {
                        return false
                    }
                    var j = k.name;
                    if (k.localityType.name) {
                        j = k.localityType.name + " " + j
                    }
                    return {
                        label: j,
                        value: c(k.codes.kladr)
                    }
                }).filter(function(j) {
                    return j !== false
                });
                e(i)
            },
            error: function() {
                e([])
            }
        });
        d.data("xhr", g)
    });
    $("#intaro_crmbundle_ordertype_deliveryAddress_cityId").change(function(g, f) {
        if (typeof(f) != "undefined") {
            if (typeof(f.codes) == "undefined" || typeof(f.codes.kladr) == "undefined") {
                alert(_translate("alert.no_kladr_for_location"));
                g.preventDefault();
                g.stopPropagation();
                $("#intaro_crmbundle_ordertype_deliveryAddress_city").val("");
                $("#intaro_crmbundle_ordertype_deliveryAddress_cityId").val("")
            } else {
                var d = f.codes.kladr;
                $(b + "receiverCityKladr").val(c(d)).change()
            }
        }
    });
    $("#dellin-sender-terminal select, #dellin-receiver-terminal select").change(function() {
        $container = $(this).parents(".input-group").parent();
        $container.find('input[type="hidden"]').val($(this).val()).change()
    });
    $(b + "senderTerminal, " + b + "receiverTerminal").change(function() {
        if ($(this).val() != "") {
            $(this).parent().find("span").html($(this).val().split("|")[0] + "<br>" + $(this).val().split("|")[1]).removeClass("hide")
        } else {
            $(this).parent().find("span").html("").addClass("hide")
        }
    }).change();
    $(b + "deliveryForm").change(function() {
        if ($(this).val() == "store-store" || $(this).val() == "store-door") {
            $(b + "senderTerminal").parent().show()
        } else {
            $(b + "senderTerminal").parent().hide()
        }
        if ($(this).val() == "store-store" || $(this).val() == "door-store") {
            $(b + "receiverTerminal").parent().show();
            IntegrationDelivery.toggleAddresVisibility(true);
            $("#city-text, #region-text").show()
        } else {
            $(b + "receiverTerminal").parent().hide();
            IntegrationDelivery.toggleAddresVisibility();
            $("#city-text, #region-text").show()
        }
    }).change();
    $("#delivery-cost, #delivery-net-cost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "dellin") {
            return
        }
        if ($(b + "payerType").val() == "receiver") {
            Order.updateDeliveryCost($(this), priceFormat(0, undefined, true))
        }
    }).change();
    $("#intaro_crmbundle_ordertype_shipmentStore").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "dellin") {
            return
        }
        a(false, true)
    });
    $(b + "receiverCityKladr").change(function() {
        a(true);
        if ($(this).val() != "") {
            $("#dellin-link-calculate").click()
        }
    });
    $(b + "deliveryForm, #intaro_crmbundle_ordertype_weight, #intaro_crmbundle_ordertype_length, #intaro_crmbundle_ordertype_width, #intaro_crmbundle_ordertype_height").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "dellin") {
            return
        }
        a()
    });
    $(b + "payerType").change(function() {
        if ($(this).val() == "receiver") {
            $("#delivery-cost").change();
            $("#delivery-net-cost").change()
        }
    });
    $("#intaro_crmbundle_ordertype_deliveryType").change(function() {
        $("#order-product-summ").die("totalProductSummUpdated");
        $("#intaro_crmbundle_ordertype_weight").unbind("change");
        $("#intaro-crm-deliveries .deliveries-popup-open").unbind("click")
    })
};
IntegrationDelivery.modules.dpd = function() {
    var d = IntegrationDelivery.prefix;
    IntegrationDelivery.popupRequiredFields = function() {
        var k = {
            intaro_crmbundle_ordertype_deliveryAddress_city: "delivery_city",
            intaro_crmbundle_ordertype_paymentType: "payment_type"
        };
        if (!$("#intaro_crmbundle_ordertype_integrationDeliveryData_parcels > div.input-group").length) {
            k.intaro_crmbundle_ordertype_weight = "weight"
        } else {
            $("#intaro_crmbundle_ordertype_integrationDeliveryData_parcels > div.input-group div.dimensions input").each(function() {
                var l = $(this).attr("id");
                k[l] = "parcel" + l.slice(l.lastIndexOf("_"))
            })
        }
        k.intaro_crmbundle_ordertype_shipmentStore = "store";
        if ($(d + "selfPickup").is(":checked")) {
            k.intaro_crmbundle_ordertype_integrationDeliveryData_terminal = "from_terminal"
        }
        return k
    };
    $("#intaro_crmbundle_ordertype_deliveryType").change(function() {
        $("#intaro-crm-deliveries .deliveries-popup-open").unbind("click")
    });
    $("#change-external-id-link").click(function() {
        $("#change-external-id").slideToggle()
    });
    IntegrationDelivery.saveFields();
    var e = function() {
        if ($("#intaro-crm-deliveries").data("is-shipping") == true) {
            return
        }
        IntegrationDelivery.saveFields();
        $("#delivery-date,             #delivery-schedules,            #delivery-address,            #integration-delivery-id,            #delivery-status,             #dpd-delivery-price,             #delivery-commission,             #delivery-days").text("").parent().addClass("hide");
        $("#delivery-name").text(_translate("info.no_select_mas"));
        $(d + "pickuppoint,  " + d + "deliveryName,  " + d + "deliveryCode,  " + d + "days,  " + d + "price,  " + d + "pointAddress,  " + d + "schedules,  ").val("");
        $(d + "commission").val(0);
        $(d + "date").datepicker("option", {
            minDate: 0
        }).change();
        IntegrationDelivery.toggleCashPayment($(d + "deliveryName").data("cash-map"), false)
    };
    IntegrationDelivery.initItemsDeclaredValuePopup();
    var b = function() {
        if ($(d + "selfPickup").is(":checked")) {
            $(d + "terminal").parents(".input-group").show()
        } else {
            $(d + "terminal").parents(".input-group").hide()
        }
    };
    var j = function() {
        $("#terminal_work_time, #terminal_address").remove();
        if ($(d + "terminal").val() == "" || $(d + "terminal").val() == "false") {
            return
        }
        var k = jQuery.parseJSON($(d + "terminal").val());
        if (!k) {
            return
        }
        var p = "<ul>";
        if (Array.isArray(k.schedule)) {
            for (var n = k.schedule.length - 1; n >= 0; n--) {
                if (k.schedule[n].operation != "SelfPickup") {
                    continue
                }
                for (var m = k.schedule[n].timetable.length - 1; m >= 0; m--) {
                    p += "<li>" + k.schedule[n].timetable[m].weekDays + ": " + k.schedule[n].timetable[m].workTime + "</li>"
                }
            }
        } else {
            p = ""
        }
        if (p != "") {
            var o = '<div class="input-group cleared both" id="terminal_work_time">                 <label class="label-common">' + _translate("label.terminal_working_time") + '</label>                 <span class="gray-bg fs13 static">' + p + "</span>             </div>";
            $(d + "terminal").parent().after(o)
        }
        if (k.shipmentDescription != "") {
            var l = '<div class="input-group cleared both" id="terminal_address">                 <label class="label-common">' + _translate("label.terminal_address") + '</label>                 <span class="gray-bg fs13 static">' + k.shipmentDescription + "</span>             </div>";
            $(d + "terminal").parent().after(l)
        }
        $(d + "terminal").prev().val(k.terminalName)
    };
    var a = function() {
        if ($('#extra-services input:checkbox[value="־ִֶ"]').is(":checked")) {
            $("#delay-reasons").removeClass("hide")
        } else {
            $("#delay-reasons").addClass("hide")
        }
    };
    b();
    enableAutocomplete();
    enableHints();
    a();
    var i = new DeliveryPackages($("#deliveries-parcels-edit"), $("#deliveries-parcels"));
    i.init();
    $("#deliveries-parcels").bind("deliveryPackage.save", function() {
        IntegrationDelivery.dataIsChange(true);
        e()
    });
    if ($("#intaro_crmbundle_ordertype_integrationDeliveryData_parcels > div.input-group").length) {
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_parcels > div.input-group").each(function() {
            parcelLogick($(this))
        });
        toggleInputRequired($("#intaro_crmbundle_ordertype_weight").parent(), false)
    }
    $('#extra-services input:checkbox[value="־ִֶ"]').change(function() {
        a()
    });
    $(d + "pickupDate").datepicker({
        dateFormat: _getDateFormatString(),
        minDate: new Date()
    });
    $(d + "terminal").change(j);
    j();
    if ($(d + "pickuppoint").val() != "") {
        IntegrationDelivery.toggleAddresVisibility(true)
    } else {
        IntegrationDelivery.toggleAddresVisibility(false)
    }
    $("#delivery-cost, #delivery-net-cost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "dpd") {
            return
        }
        if ($(d + "payerType").val() == "receiver" && $("#intaro-crm-deliveries").data("is-shipping") == false) {
            if ($("#intaro-crm-deliveries").data("add-cash-commission")) {
                Order.updateDeliveryCost($("#delivery-cost"), $(d + "commission").val())
            } else {
                Order.updateDeliveryCost($("#delivery-cost"), 0)
            }
            Order.updateDeliveryCost($("#delivery-net-cost"), $(d + "commission").val());
            recalcTotalSumm()
        }
    });
    $(d + "payerType").change(function() {
        if ($(this).val() == "receiver") {
            $("#delivery-cost").change();
            $("#delivery-net-cost").change()
        } else {
            if ($("#delivery-cost").attr("data-actual-cost") != undefined) {
                $("#delivery-cost").val($("#delivery-cost").attr("data-actual-cost")).change()
            }
            if ($("#delivery-net-cost").attr("data-actual-cost") != undefined) {
                $("#delivery-net-cost").val($("#delivery-net-cost").attr("data-actual-cost")).change()
            }
        }
        e();
        IntegrationDelivery.dataIsChange(true)
    });
    $(d + "selfPickup").change(function() {
        b();
        j();
        e();
        IntegrationDelivery.dataIsChange(true)
    });
    var g = function() {
        if ($("#intaro-crm-deliveries").data("code") != "dpd") {
            return
        }
        e();
        IntegrationDelivery.dataIsChange(true)
    };
    $("#payments").bind("collection:changed", g);
    var h = function() {
        $(d + "selfPickup").parent().removeClass("hide");
        var l = $("#intaro-crm-deliveries").data("only-terminal");
        if (l !== undefined) {
            for (index in l) {
                if (l[index] == $("#intaro_crmbundle_ordertype_shipmentStore").val()) {
                    $(d + "selfPickup").parent().addClass("hide");
                    $(d + "selfPickup").attr("checked", "checked")
                }
            }
        }
        if (this.initialized || $(d + "terminal").val() == "") {
            if ($("#intaro-crm-deliveries").data("default-terminals")[$(this).val()] != undefined) {
                terminal = $("#intaro-crm-deliveries").data("default-terminals")[$(this).val()];
                var m = $(d + "terminal").prev().autocomplete("instance").options.source;
                for (var k = 0; k < m.length; k++) {
                    if (m[k].code == terminal.value) {
                        $(d + "terminal").prev().val(m[k].label);
                        $(d + "terminal").val(m[k].value).change();
                        break
                    }
                }
            } else {
                $(d + "terminal").prev().val("");
                $(d + "terminal").val("").change()
            }
        }
        this.initialized = true
    };
    if ($("#intaro_crmbundle_ordertype_shipmentStore").length) {
        $("#intaro_crmbundle_ordertype_shipmentStore").change(function() {
            if ($("#intaro-crm-deliveries").data("code") != "dpd") {
                return
            }
            h.call(this);
            $(d + "selfPickup").change()
        });
        h.call($("#intaro_crmbundle_ordertype_shipmentStore")[0])
    }
    var f = function() {
        var k = $("#intaro_crmbundle_ordertype_country");
        if (k.val() != "RU") {
            $(d + "receiverCity").prev(".autocomplete").autocomplete("option", "disabled", true)
        } else {
            $(d + "receiverCity").prev(".autocomplete").autocomplete("option", "disabled", false)
        }
    };
    b();
    $("#intaro_crmbundle_ordertype_country").change(f);
    f();
    $(d + "receiverCity").prev(".autocomplete").change(function() {
        if ($(this).autocomplete("option", "disabled")) {
            $("#intaro_crmbundle_ordertype_deliveryAddress_city").val($(this).val())
        }
    });
    $(d + "receiverCity").change(function() {
        var k = $(this).prev("input").val().split(", ");
        if (k.length) {
            $("#intaro_crmbundle_ordertype_deliveryAddress_city").val(k[0])
        }
        if (k.length == 2) {
            $("#region-text input").val(k[1]);
            $("#integration-delivery__region-info span.static").text(k[1])
        }
        e();
        IntegrationDelivery.dataIsChange(true)
    });
    $("#intaro_crmbundle_ordertype_shipmentStore,#intaro_crmbundle_ordertype_weight,#intaro_crmbundle_ordertype_length,#intaro_crmbundle_ordertype_width,#intaro_crmbundle_ordertype_height").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "dpd") {
            return
        }
        if ($("#intaro_crmbundle_ordertype_integrationDeliveryData_parcels > div.input-group").length) {
            return
        }
        e();
        IntegrationDelivery.dataIsChange(true)
    });
    if ($("#city-text input").val() != "" && $(d + "receiverCity").val() != "") {
        $(d + "receiverCity").prev(".autocomplete").val($("#city-text input").val() + ", " + $("#region-text input").val())
    }
    if ($(d + "externalId").val() == "" && $("#delivery-status").text() == "") {
        if ($("#city-text input").val() != "" && $(d + "receiverCity").val() == "") {
            invokeAutocompleteWithTerm($(d + "receiverCity").prev(".autocomplete"), $("#delivery-address-form").data("addressForm").getCity(false))
        }
    } else {
        $(d + "receiverCity").prev().val($("#city-text input").val())
    }
    var c = function() {
        if ($(d + "externalId").val() == "" && $("#delivery-status").text() == "" && $("#city-text input").val() != "" && $(d + "receiverCity").val() == "") {
            invokeAutocompleteWithTerm($(d + "receiverCity").prev(".autocomplete"), $("#delivery-address-form").data("addressForm").getCity(false))
        }
    };
    $("#parse-address-btn").bind("addressParsed", c);
    $(d + "receiverCity").parent().delegate("input", "input", function() {
        $("#intaro_crmbundle_ordertype_deliveryAddress_city").trigger("input")
    });
    $(d + "terminal").parent().delegate("input:first", "input", function() {
        $(d + "terminal").trigger("input")
    });
    $("#intaro-crm-deliveries .deliveries-popup-open").click(function(l) {
        l.preventDefault();
        var k = function(p) {
            var o = $(".integration-delivery").data(d + "deliveryCode_prev");
            var n = $(".integration-delivery").data(d + "pickuppoint_prev");
            if (!o && !n) {
                return
            }
            var m = "tr";
            if (o) {
                m += '[data-service-code = "' + o + '"]'
            }
            if (n) {
                m += '[data-terminal-code = "' + n + '"]'
            }
            p.find(m).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), k)
    });
    IntegrationDelivery.deliveriesPopupFilter();
    $("#intaro-crm-deliveries #open-delivery-history").click(function(k) {
        k.preventDefault();
        var l = $("#integration-delivery-history");
        l.intaroPopup();
        l.find(".status-tbl").show()
    });
    $(d + "date").change(function() {
        $("#intaro_crmbundle_ordertype_deliveryDate").val($(this).val()).change()
    });
    $("#available-deliveries").delegate(".modern-table tr td", "click", function() {
        e();
        var k = $("#available-deliveries");
        var p = $(this).parent();
        if (p.data("service-code") !== "") {
            $(d + "deliveryCode").val(p.data("service-code"))
        }
        $("#delivery-name").text(p.data("service-name"));
        if (p.data("service-name") !== "") {
            $(d + "deliveryName").val(p.data("service-name"))
        }
        var s = parseFloat(p.data("commission")).toFixed(2);
        if (s > 0) {
            $("#delivery-commission").html(priceFormat(s)).parent().removeClass("hide")
        } else {
            $("#delivery-commission").parent().addClass("hide")
        }
        $(d + "commission").val(s);
        var q;
        if (p.data("im-price") !== "") {
            q = p.data("im-price")
        } else {
            q = p.data("price")
        }
        $("#dpd-delivery-price").html(priceFormat(q)).parent().removeClass("hide");
        $(d + "price").val(q).change();
        if ($("#intaro-crm-deliveries").data("add-cash-commission")) {
            $("#delivery-net-cost").val(q).change()
        } else {
            $("#delivery-net-cost").val(parseFloat(q + p.data("commission")).toFixed(2)).change()
        }
        Order.setDeliveryCost(q);
        $("#delivery-days").text(p.data("days")).parent().removeClass("hide");
        $(d + "days").val(p.data("days"));
        if (p.data("type") === "terminal") {
            var l = p.data("terminal");
            $(d + "pickuppoint").val(l.terminalCode);
            $("#delivery-address").text(l.terminalAddress).parent().removeClass("hide");
            $(d + "pointAddress").val(l.terminalAddress);
            var n = "<ul>";
            var r = false;
            if (l.schedule instanceof Array) {
                for (var o = l.schedule.length - 1; o >= 0; o--) {
                    if (l.schedule[o].operation != "SelfDelivery") {
                        continue
                    }
                    r = true;
                    for (var m = l.schedule[o].timetable.length - 1; m >= 0; m--) {
                        n += "<li>" + l.schedule[o].timetable[m].weekDays + ": " + l.schedule[o].timetable[m].workTime + "</li>"
                    }
                }
            }
            n += "</ul>";
            $("#delivery-schedules").html(n).parent().removeClass("hide");
            $(d + "schedules").val(n);
            IntegrationDelivery.toggleAddresVisibility(true)
        } else {
            IntegrationDelivery.toggleAddresVisibility(false)
        }
        IntegrationDelivery.saveFields();
        k.find(".close").click()
    });
    $("#intaro_crmbundle_ordertype_deliveryType").one("change", function() {
        $("#intaro_crmbundle_ordertype_country").unbind("change", f);
        $("#intaro_crmbundle_ordertype_weight,#intaro_crmbundle_ordertype_shipmentStore," + d + "receiverCity").unbind("change");
        $("#payments").unbind("collection:changed", g);
        $("#parse-address-btn").unbind("addressParsed", c)
    })
};
IntegrationDelivery.modules.generic = function() {
    var j = IntegrationDelivery.prefix;
    var d;
    IntegrationDelivery.popupRequiredFields = function() {
        var n = {
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_weight: "weight",
            intaro_crmbundle_ordertype_deliveryAddress_city: "delivery_city",
            intaro_crmbundle_ordertype_paymentType: "payment_type"
        };
        $("input[data-affects-cost]").each(function() {
            n[$(this).attr("id")] = _translate("message.need_set_field") + " " + $(this).siblings('label[for = "' + $(this).attr("id") + '"]').text().toLowerCase()
        });
        return n
    };
    var k = $("#intaro_crmbundle_ordertype_weight,#intaro_crmbundle_ordertype_shipmentStore,#intaro_crmbundle_ordertype_paymentType,#intaro_crmbundle_ordertype_deliveryAddress_cityId,#intaro_crmbundle_ordertype_deliveryAddress_index,#intaro_crmbundle_ordertype_deliveryAddress_index,#intaro_crmbundle_ordertype_integrationDeliveryData_shipmentpointId,input[data-affects-cost],select[data-affects-cost]");
    var g = ["intaro_crmbundle_ordertype_deliveryAddress_regionId", "intaro_crmbundle_ordertype_deliveryAddress_cityId", "intaro_crmbundle_ordertype_deliveryAddress_streetId"];
    var f = function() {
        var r = $(".integration-delivery");
        if (r.length && "undefined" != typeof r.data("required-fields")) {
            var p = r.data("required-fields").split(",");
            for (var q = 0; q < g.length; q++) {
                if (p.indexOf(g[q]) != -1) {
                    var o = $("#" + g[q].slice(0, -2));
                    var n = $("#" + g[q]);
                    o.attr("data-autocomplete-strict", 1);
                    if (n.length && n.val() == "" && o.val() != "") {
                        invokeAutocompleteWithTerm(o, o.val())
                    }
                }
            }
        }
    };
    IntegrationDelivery.saveFields();
    var e = function() {
        if (!$("#intaro-crm-deliveries").data("editable")) {
            return
        }
        IntegrationDelivery.saveFields();
        $(j + "tariff").val("");
        $(j + "tariffType").val("");
        $(j + "tariffName").val("");
        $("#intaro-crm-deliveries .integration_delivery__tariff").text(_translate("info.no_select_mas"));
        $(j + "minTerm").val("");
        $(j + "maxTerm").val("");
        $("#integration-delivery-days").text("").parent().addClass("hide");
        $(j + "cost").val("");
        $("#integration-delivery-cost").html("").parent().addClass("hide");
        $(j + "pickuppointId").val("");
        $(j + "pickuppointName").val("");
        $(j + "pickuppointAddress").val("");
        $("#intaro-crm-deliveries .integration_delivery__terminal_address").text("").parent().addClass("hide");
        $(j + "pickuppointSchedule").val("");
        $("#intaro-crm-deliveries .integration_delivery__terminal_schedule").text("").parent().addClass("hide");
        $(j + "pickuppointPhone").val("");
        $("#intaro-crm-deliveries .integration_delivery__terminal_phone").text("").parent().addClass("hide");
        $(j + "pickuppointCoordinateLatitude").val("");
        $(j + "pickuppointCoordinateLongitude").val("");
        $(".integration_delivery__extra_data").val("");
        $("#intaro-crm-deliveries .integration_delivery__extra_data_view").addClass("hide").find("span").text("");
        IntegrationDelivery.toggleAddresVisibility(false)
    };
    var a = function() {
        if ($(j + "payerType").val() == "receiver" && $("#intaro-crm-deliveries").data("editable")) {
            $(this).val(priceFormat(0, undefined, true));
            if ($(this).is("#delivery-cost")) {
                recalcTotalSumm()
            }
        }
    };
    var b = function() {
        e();
        IntegrationDelivery.dataIsChange(true)
    };
    var m = function(o, n) {
        $shipmentPoint = $(j + "shipmentpoint");
        if (!$shipmentPoint.length) {
            return
        }
        if (n == undefined || n.selected == undefined) {
            n = $(this).val()
        } else {
            n = n.selected
        }
        if (n == "") {
            $shipmentPoint.attr("disabled", true);
            return
        } else {
            $shipmentPoint.attr("disabled", false)
        }
        $shipmentPoint.autocomplete("option", "source", []);
        $shipmentPoint.parent().addClass("loading");
        if (d) {
            d.abort()
        }
        d = $.get($shipmentPoint.data("url"), {
            storeId: n
        }).success(function(s) {
            $shipmentPoint.autocomplete("option", "source", s);
            var q = $("#intaro-crm-deliveries").data("default-terminals");
            if (q[n] == undefined) {
                return
            }
            for (var p = s.length - 1; p >= 0; p--) {
                if (s[p]["value"] == q[n]) {
                    var r = $shipmentPoint.autocomplete("option", "valueField").val();
                    if (r != s[p]["value"]) {
                        $shipmentPoint.val(s[p]["label"]).autocomplete("option", "valueField").val(s[p]["value"]).trigger("change", [s[p]])
                    }
                    break
                }
            }
        }).always(function() {
            $shipmentPoint.parent().removeClass("loading")
        })
    };
    $("#intaro_crmbundle_ordertype_deliveryType").one("change", function() {
        for (var n = 0; n < g.length; n++) {
            $("#" + g[n].slice(0, -2)).attr("data-autocomplete-strict", 0)
        }
        k.unbind("change", b);
        $("#intaro_crmbundle_ordertype_shipmentStore").unbind("change", m);
        IntegrationDelivery.toggleAddresVisibility(false)
    });
    enableAutocomplete();
    $("#intaro-crm-deliveries .datepicker:not(.datepicker-custom)").datepicker({
        dateFormat: _getDateFormatString()
    });
    f();
    if ($(j + "pickuppointId").val() != "") {
        IntegrationDelivery.toggleAddresVisibility(true, true)
    }
    var l = new DeliveryPackages($("#deliveries-parcels-edit"), $("#deliveries-parcels"));
    l.init();
    $("#deliveries-parcels").bind("deliveryPackage.save", function() {
        IntegrationDelivery.dataIsChange(true);
        e()
    });
    k.change(b);
    var i = $("#intaro_crmbundle_ordertype_shipmentStore");
    if (i.length) {
        $("#intaro_crmbundle_ordertype_shipmentStore").change(m);
        m.call($("#intaro_crmbundle_ordertype_shipmentStore")[0])
    } else {
        var h = $("#order-shipment-store");
        if (h.length) {
            m.call(null, null, {
                selected: h.data("id")
            })
        }
    }
    $("#delivery-cost, #delivery-net-cost").change(a);
    $(j + "payerType").change(function() {
        if ($(this).val() == "receiver") {
            $("#delivery-cost, #delivery-net-cost").change()
        }
        b()
    });
    $(j + "extraDataAvailable").change(function() {
        var n = $(".input-delivery-extra-data, .integration_delivery__extra_data_editable_view");
        var q = null;
        try {
            q = JSON.parse($(this).val())
        } catch (r) {
            q = null
        }
        if (!Array.isArray(q)) {
            n.show();
            toggleInputRequired(n)
        } else {
            n.hide();
            toggleInputRequired(n, false);
            for (var o = q.length - 1; o >= 0; o--) {
                var p = $(j + q[o]);
                p.parents(".input-group:first").show();
                toggleInputRequired(p);
                $(".integration_delivery__extra_data_editable_" + q[o]).show()
            }
        }
    }).change();
    var c = $(j + "shipmentpointId");
    if (c.length) {
        c.change(function(n, o) {
            $(".integration_delivery__shipment_terminal_address, .integration_delivery__shipment_terminal_schedule,.integration_delivery__shipment_terminal_phone", "#intaro-crm-deliveries").text("").parent().addClass("hide");
            if ($(this).val() == "" || o == undefined || o.data == undefined) {
                return
            }
            $(j + "shipmentpointId").val(o.data.code);
            $(j + "shipmentpointAddress").val(o.data.address);
            if (o.data.address) {
                $("#intaro-crm-deliveries .integration_delivery__shipment_terminal_address").text(o.data.address).parent().removeClass("hide")
            }
            $(j + "shipmentpointSchedule").val(o.data.schedule);
            if (o.data.schedule) {
                $("#intaro-crm-deliveries .integration_delivery__shipment_terminal_schedule").text(o.data.schedule).parent().removeClass("hide")
            }
            $(j + "shipmentpointPhone").val(o.data.phone);
            if (o.data.phone) {
                $("#intaro-crm-deliveries .integration_delivery__shipment_terminal_phone").text(o.data.phone).parent().removeClass("hide")
            }
            if (o.data.coordinates != undefined) {
                $(j + "shipmentpointCoordinateLatitude").val(o.data.coordinates.latitude);
                $(j + "shipmentpointCoordinateLongitude").val(o.data.coordinates.longitude)
            }
        })
    }
    $("#intaro-crm-deliveries .deliveries-popup-open").click(function(o) {
        o.preventDefault();
        var n = function(s) {
            var q = $(".integration-delivery").data(j + "tariff_prev");
            var r = $(".integration-delivery").data(j + "pickuppointId_prev");
            if (!q && !r) {
                return
            }
            var p = "tr";
            if (q) {
                p += '[data-code = "' + q + '"]'
            }
            if (r) {
                p += '[data-pickuppoint-code = "' + r + '"]'
            }
            s.find(p).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), n)
    });
    IntegrationDelivery.deliveriesPopupFilter();
    IntegrationDelivery.initItemsDeclaredValuePopup();
    $("#available-deliveries").delegate(".modern-table tr:not(.disabled) td", "click", function(r) {
        e();
        var s = $("#available-deliveries");
        var p = $(this).parent();
        $(j + "tariff").val(p.attr("data-code"));
        $(j + "tariffType").val(p.attr("data-type"));
        $(j + "tariffName").val(p.attr("data-name"));
        $("#intaro-crm-deliveries .integration_delivery__tariff").text(p.attr("data-name"));
        if (typeof p.attr("data-minTerm") != "undefined" || typeof p.attr("data-maxTerm") != "undefined") {
            $(j + "minTerm").val(p.attr("data-minTerm"));
            $(j + "maxTerm").val(p.attr("data-maxTerm"));
            $("#integration-delivery-days").text(p.find("td.delivery_popup__term").text()).parent().removeClass("hide")
        }
        var t = parseFloat(p.attr("data-cost")).toFixed(2);
        $(j + "cost").val(t);
        Order.setDeliveryNetCost(t);
        if ($("#intaro-crm-deliveries").data("delivery-cost") == "auto") {
            Order.setDeliveryCost(t)
        }
        $("#integration-delivery-cost").html(priceFormat(p.attr("data-cost"))).parent().removeClass("hide");
        var o = p.data("extra");
        if (o != undefined) {
            for (fieldCode in o) {
                $(j + fieldCode).val(o[fieldCode]);
                var n = $("#intaro-crm-deliveries .integration_delivery__extra_" + fieldCode).removeClass("hide").find("span.static");
                if (n.length > 0) {
                    var q = o[fieldCode];
                    if (n.data("type") == "checkbox") {
                        q = (q === true || q == "true") ? "info.yes" : "info.no"
                    }
                    n.text(_translate(q))
                }
            }
        }
        if (p.attr("data-extra-available") != undefined) {
            $(j + "extraDataAvailable").val(p.attr("data-extra-available")).change()
        }
        if (p.attr("data-type") == "selfDelivery") {
            $(j + "pickuppointId").val(p.attr("data-pickuppoint-code"));
            $(j + "pickuppointName").val(p.attr("data-pickuppoint-name"));
            $(j + "pickuppointAddress").val(p.attr("data-pickuppoint-address"));
            if (p.attr("data-pickuppoint-address") != "") {
                $("#intaro-crm-deliveries .integration_delivery__terminal_address").text(p.attr("data-pickuppoint-address")).parent().removeClass("hide")
            }
            $(j + "pickuppointSchedule").val(p.attr("data-pickuppoint-schedule"));
            if (p.attr("data-pickuppoint-schedule") != "") {
                $("#intaro-crm-deliveries .integration_delivery__terminal_schedule").text(p.attr("data-pickuppoint-schedule")).parent().removeClass("hide")
            }
            $(j + "pickuppointPhone").val(p.attr("data-pickuppoint-phone"));
            if (p.attr("data-pickuppoint-phone") != "") {
                $("#intaro-crm-deliveries .integration_delivery__terminal_phone").text(p.attr("data-pickuppoint-phone")).parent().removeClass("hide")
            }
            $(j + "pickuppointCoordinateLatitude").val(p.attr("data-pickuppoint-coordinates-latitude"));
            $(j + "pickuppointCoordinateLongitude").val(p.attr("data-pickuppoint-coordinates-longitude"));
            IntegrationDelivery.toggleAddresVisibility(true, true)
        } else {
            IntegrationDelivery.toggleAddresVisibility(false)
        }
        s.find(".close").click()
    })
};
IntegrationDelivery.modules.newpost = function() {
    var d = IntegrationDelivery.prefix;
    enableAutocomplete();

    function f(h, i) {
        h.hide().find("input, select").prop("required", false);
        if (!h.data("hide-codes")) {
            h.data("hide-codes", [i])
        } else {
            var g = h.data("hide-codes");
            g.push(i);
            h.data("hide-codes", g)
        }
    }

    function b(l, g, m) {
        if (!l.data("hide-codes")) {
            l.data("hide-codes", [])
        } else {
            var h = l.data("hide-codes");
            var j = [];
            for (var k = 0; k < h.length; k++) {
                if (h[k] != g) {
                    j.push(h[k])
                }
            }
            l.data("hide-codes", j)
        }
        if (l.data("hide-codes") != undefined && l.data("hide-codes").length == 0) {
            l.slideDown();
            if (m == true && !IntegrationDelivery.isLocked()) {
                if (l.find("input").length) {
                    l.find("input").prop("required", true)
                } else {
                    l.find("select").prop("required", true)
                }
                l.find("label").addClass("required")
            }
        }
    }
    var c = function(g, h) {
        if (h == "" || !$("#intaro-crm-deliveries").data("editable")) {
            return
        }
        g.autocomplete("option", "source", []);
        g.parent().addClass("loading");
        $.get(g.attr("data-source-url"), {
            city: h
        }).success(function(l) {
            if (l.length > 0) {
                g.val("");
                g.autocomplete("option", "source", l);
                var j = g.autocomplete("option", "valueField");
                for (var k = l.length - 1; k >= 0; k--) {
                    if (l[k]["value"] == j.val()) {
                        g.val(l[k]["label"]);
                        break
                    }
                }
                if (g.val() == "") {
                    j.val("")
                }
                if (g.val() != "" && j.val() == "") {
                    invokeAutocompleteWithTerm(g, g.val())
                }
            }
        }).always(function() {
            g.parent().removeClass("loading")
        })
    };
    $(d + "sendDate," + d + "preferredDeliveryDate").datepicker({
        dateFormat: "dd.mm.yy",
        minDate: new Date()
    });
    $(d + "serviceType").change(function() {
        if ($(this).val() == "WarehouseWarehouse" || $(this).val() == "WarehouseDoors") {
            b($(d + "senderWarehouse").parents(".input-group"), "serviceType", true)
        } else {
            f($(d + "senderWarehouse").parents(".input-group"), "serviceType")
        }
        if ($(this).val() == "WarehouseWarehouse" || $(this).val() == "DoorsWarehouse") {
            b($(d + "receiverWarehouse").parents(".input-group"), "serviceType", true);
            f($(d + "receiverStreet").parents(".input-group"), "serviceType");
            IntegrationDelivery.toggleAddresVisibility(true);
            IntegrationDelivery.requiredFields = [];
            IntegrationDelivery.updateRequiredFields(true)
        } else {
            f($(d + "receiverWarehouse").parents(".input-group"), "serviceType");
            b($(d + "receiverStreet").parents(".input-group"), "serviceType", true);
            IntegrationDelivery.toggleAddresVisibility(false);
            IntegrationDelivery.requiredFields = ["building-text input"];
            IntegrationDelivery.updateRequiredFields(true)
        }
    }).change();
    $("#intaro_crmbundle_ordertype_integrationDeliveryData_locked").change(function() {
        if (!$(this).is(":checked")) {
            $(d + "serviceType").change()
        }
    });
    $(d + "receiverWarehouseRef").change(function(h, g) {
        if (typeof g != "undefined" && typeof g.typeRef != "undefined" && g.typeRef != "") {
            $(d + "receiverWarehouseTypeRef").val(g.typeRef).change()
        } else {
            $(d + "receiverWarehouseTypeRef").val("").change()
        }
    });
    $(d + "receiverWarehouseTypeRef").change(function() {
        IntegrationDelivery.updateRequiredFields();
        if ($("#intaro-crm-deliveries").data("pochtomat-id").indexOf($(this).val()) == -1) {
            $(d + "seatsAmount").parent().show();
            IntegrationDelivery.requiredFields = []
        } else {
            $(d + "seatsAmount").parent().hide();
            IntegrationDelivery.requiredFields = ["intaro_crmbundle_ordertype_length", "intaro_crmbundle_ordertype_width", "intaro_crmbundle_ordertype_height"]
        }
        IntegrationDelivery.updateRequiredFields(true)
    }).change();
    $("#intaro_crmbundle_ordertype_shipmentStore").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "newpost") {
            return
        }
        if ($(this).val() == "") {
            $(d + "senderWarehouse").attr("disabled", "disabled");
            return
        }
        $(d + "senderWarehouse").removeAttr("disabled");
        var g = $("#intaro-crm-deliveries").data("city-mapping");
        if (g !== undefined && g[$(this).val()] != undefined) {
            c($(d + "senderWarehouse"), g[$(this).val()])
        }
        if (this.initialized || $(d + "senderWarehouseRef").val() == "") {
            if ($("#intaro-crm-deliveries").data("default-terminals")[$(this).val()] != undefined) {
                terminal = $("#intaro-crm-deliveries").data("default-terminals")[$(this).val()];
                $(d + "senderWarehouse").val(terminal.label);
                $(d + "senderWarehouseRef").val(terminal.value).change()
            } else {
                $(d + "senderWarehouse").val("");
                $(d + "senderWarehouseRef").val("").change()
            }
        }
        this.initialized = true
    }).change();
    $(d + "receiverCityRef").change(function() {
        if ($(this).val() == "") {
            var h = '<div class="msg-warning message-city-required">' + _translate("message.need_set_delivery_city") + "</div>";
            $(d + "receiverWarehouse").prop("disabled", "disabled");
            $(d + "receiverStreet").val("").prop("disabled", "disabled");
            $(d + "timeInterval").val("").prop("disabled", "disabled");
            if (!$(d + "receiverWarehouse").parents(".input-group").find("div.message-city-required").length) {
                $(d + "receiverWarehouse").parents(".input-group").append(h)
            }
            if (!$(d + "receiverStreet").parents(".input-group").find("div.message-city-required").length) {
                $(d + "receiverStreet").parents(".input-group").append(h)
            }
            if (!$(d + "timeInterval").parents(".input-group").find("div.message-city-required").length) {
                $(d + "timeInterval").parents(".input-group").append(h)
            }
            return
        }
        $(".message-city-required").remove();
        $(d + "receiverWarehouse").prop("disabled", false);
        c($(d + "receiverWarehouse"), $(this).val());
        $(d + "receiverStreet").prop("disabled", false);
        $(d + "timeInterval").prop("disabled", false);
        if ($(d + "receiverCity").val() !== "") {
            $("#city-text input").val($(d + "receiverCity").val())
        }
        var g = $(d + "receiverStreet").data("autocomplete-source");
        $(d + "receiverStreet").data("autocomplete-source", changeUrlParameter(g, "city", $(this).val())).autocomplete("option", "source", $(d + "receiverStreet").data("autocomplete-source"));
        var g = $(d + "timeInterval").data("autocomplete-source");
        $(d + "timeInterval").data("autocomplete-source", changeUrlParameter(g, "city", $(this).val())).autocomplete("option", "source", $(d + "timeInterval").data("autocomplete-source"));
        if ($("#street-text input").val() != "" && $(d + "receiverStreet").val() == "" && $(d + "receiverStreet").is(":visible")) {
            invokeAutocompleteWithTerm($(d + "receiverStreet"), $("#delivery-address-form").data("addressForm").getStreet(false))
        }
    }).change();
    $(d + "receiverStreetRef").change(function() {
        if ($(d + "receiverStreet").val() !== "") {
            $("#street-text input").val($(d + "receiverStreet").val())
        }
    });
    $("#intaro_crmbundle_ordertype_contragent_contragentType").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "newpost") {
            return
        }
        if ($(this).val() == "legal-entity") {
            $(d + "ownershipForm").prop("required", true).siblings("label").addClass("required").parent().show();
            $("#intaro_crmbundle_ordertype_contragent_legalName").prop("required", true).siblings("label").addClass("required")
        } else {
            $(d + "ownershipForm").prop("required", false).siblings("label").removeClass("required").parent().hide();
            $("#intaro_crmbundle_ordertype_contragent_legalName").prop("required", false).siblings("label").removeClass("required")
        }
    }).change();
    $("#delivery-cost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "newpost") {
            return
        }
        if ($(d + "newPostPayerType").val() == "Recipient") {
            Order.updateDeliveryCost($("#delivery-cost"), priceFormat(0, undefined, true));
            recalcTotalSumm()
        }
    }).change();
    $(d + "preferredDeliveryDate").change(function() {
        var g = $(d + "timeInterval").data("autocomplete-source");
        $(d + "timeInterval").data("autocomplete-source", changeUrlParameter(g, "date", $(this).val())).autocomplete("option", "source", $(d + "timeInterval").data("autocomplete-source"))
    }).change();
    $(d + "backwardDelivery").change(function() {
        if ($(this).prop("checked")) {
            $("#newpost-backward-delivery").slideDown();
            toggleInputRequired($("#newpost-backward-delivery"))
        } else {
            $("#newpost-backward-delivery").slideUp();
            toggleInputRequired($("#newpost-backward-delivery"), false)
        }
    }).change();
    var a = function() {
        if ($("#intaro-crm-deliveries").data("code") != "newpost") {
            return
        }
        var h = $("#intaro-crm-deliveries").data("cash-map");
        var g = IntegrationDelivery.isCashPaymentOrder(function(i) {
            return h && h[i]
        });
        if (g) {
            $(d + "cashPayerType").parent().show();
            $(d + "card").parent().show()
        } else {
            $(d + "cashPayerType").parent().hide();
            $(d + "card").parent().hide()
        }
    };
    $("#payments").bind("collection:changed", a);
    a();
    $(d + "backwardDeliveryCargoType").change(function() {
        if ($(this).val() == "Trays") {
            $(d + "trays").parent().show()
        } else {
            $(d + "trays").parent().hide()
        }
    }).change();
    if ($("#city-text input").val() != "" && $(d + "receiverCityRef").val() == "") {
        invokeAutocompleteWithTerm($(d + "receiverCity"), $("#delivery-address-form").data("addressForm").getCity(false))
    }
    var e = function() {
        if ($("#city-text input").val() != "" && $(d + "receiverCityRef").val() == "") {
            invokeAutocompleteWithTerm($(d + "receiverCity"), $("#delivery-address-form").data("addressForm").getCity(false))
        }
    };
    $("#parse-address-btn").bind("addressParsed", e);
    if ($(d + "receiverCity").val() != "" && $(d + "receiverCityRef").val() == "") {
        invokeAutocompleteWithTerm($(d + "receiverCity"), $(d + "receiverCity").val())
    }
    if ($(d + "receiverStreet").val() != "" && $(d + "receiverStreetRef").val() == "") {
        invokeAutocompleteWithTerm($(d + "receiverStreet"), $(d + "receiverStreet").val())
    }
    $(d + "trays a.deliveries-remove-tray").live("click", function(g) {
        g.preventDefault();
        $(this).parent().remove()
    });
    $("#deliveries-add-tray").click(function(g) {
        g.preventDefault();
        $row = addCollectionFormRow($(d + "trays"))
    });
    $("#intaro_crmbundle_ordertype_deliveryType").one("change", function() {
        $("#parse-address-btn").unbind("addressParsed", e)
    })
};
IntegrationDelivery.modules.sdek = function() {
    var g = IntegrationDelivery.prefix;
    $("#change-external-id-link").click(function() {
        $("#change-external-id").slideToggle()
    });
    IntegrationDelivery.popupRequiredFields = function() {
        var i = {
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_weight: "weight",
            intaro_crmbundle_ordertype_length: "length",
            intaro_crmbundle_ordertype_width: "width",
            intaro_crmbundle_ordertype_height: "height",
            intaro_crmbundle_ordertype_paymentType: "payment_type"
        };
        if ($("#deliveries-parcels").data("deliveryPackage").count > 0) {
            delete i.intaro_crmbundle_ordertype_weight;
            delete i.intaro_crmbundle_ordertype_length;
            delete i.intaro_crmbundle_ordertype_width;
            delete i.intaro_crmbundle_ordertype_height
        }
        i.intaro_crmbundle_ordertype_integrationDeliveryData_receiverCity = "delivery_city";
        return i
    };
    IntegrationDelivery.saveFields();
    var d = function() {
        if ($("#intaro-crm-deliveries").data("is-shipping") == true) {
            return
        }
        IntegrationDelivery.saveFields();
        $("#sdek-delivery-date,             #sdek-delivery-instruction,            #sdek-delivery-schedules,            #sdek-delivery-phone,            #sdek-delivery-days,            #sdek-delivery-address,            #sdek-delivery-price,            #sdek-delivery-insurance,            #sdek-delivery-service-price,            #integration-delivery-id,            #sdek-delivery-status").text("").parent().addClass("hide");
        $("#delivery-name").text(_translate("info.no_select_mas"));
        $("#integration-delivery-cash-limit").remove();
        $(g + "pickuppoint,  " + g + "deliveryName,  " + g + "tariffType,  " + g + "days,  " + g + "pointAddress,  " + g + "instruction,  " + g + "schedules,  " + g + "phone").val("");
        if ($(g + "date").length) {
            $(g + "date").datepicker("option", {
                minDate: 0
            }).change()
        }
        IntegrationDelivery.toggleCashPayment($(g + "deliveryName").data("cash-map"), false)
    };
    var b = function() {
        if (!$(g + "date").length) {
            return
        }
        if ($("#delivery-name").data("delivery-type") == "store-door" && $(g + "date").val() != "") {
            $(g + "timeBegin").attr("required", "required").prev().addClass("required")
        } else {
            $(g + "timeBegin").removeAttr("required").prev().removeClass("required")
        }
    };
    var f = function() {
        if ($(g + "timeBegin").length != 0 && $(g + "timeBegin").val() != "") {
            var i = $(g + "timeBegin").val();
            i = i.split(":");
            i[0] = parseInt(i[0]) + 4 + "";
            if (i[0] > 23) {
                i[0] = i[0] - 23
            }
            if (i[0] < 10) {
                i[0] = "0" + i[0]
            }
            if ($(g + "timeEnd.timepicker").length) {
                $(g + "timeEnd.timepicker").timepicker("option", "disableTimeRanges", [
                    ["00:00", i[0] + ":" + i[1]]
                ]);
                $(g + "timeEnd").val(i[0] + ":" + i[1])
            }
        }
    };
    $("#delivery-cost, #delivery-net-cost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "sdek") {
            return
        }
        if ($(g + "payerType").val() == "receiver") {
            $(this).attr("data-prev-cost", $(this).val());
            Order.updateDeliveryCost($(this), priceFormat(0, undefined, true))
        } else {
            if ($(this).attr("data-prev-cost") != undefined) {
                Order.updateDeliveryCost($(this), priceFormat($(this).attr("data-prev-cost"), undefined, true));
                $(this).removeAttr("data-prev-cost")
            }
        }
        if ($(this).attr("id") == "#delivery-cost") {
            recalcTotalSumm()
        }
    });
    $(g + "payerType").change(function() {
        $("#delivery-cost, #delivery-net-cost").change()
    });
    $(".timepicker").timepicker({});
    f();
    if ($(g + "timeBegin").length != 0) {
        $(g + "timeBegin").change(function() {
            f()
        })
    }
    $(g + "date").datepicker({});
    var h = $("#sdek-delivery-date").text().split("-");
    if (h.length == 2) {
        var c = h[0].trim().split(".");
        $(g + "date").datepicker("option", {
            minDate: new Date(c[2], c[1] - 1, c[0])
        }).change()
    } else {
        $(g + "date").datepicker("option", {
            minDate: 0
        }).change()
    }
    enableAutocomplete();
    if ($("#city-text input").val() != "" && $(g + "receiverCity").val() != "" && $(g + "receiverCityName").val() == "") {
        $(g + "receiverCityName").val($("#city-text input").val() + ", " + $("#region-text input").val())
    }
    if ($("#city-text input").val() != "" && $(g + "receiverCity").val() == "") {
        invokeAutocompleteWithTerm($(g + "receiverCityName"), $("#delivery-address-form").data("addressForm").getCity(false))
    }
    if ($("#delivery-name").data("delivery-type") == "store-store" || $("#delivery-name").data("delivery-type") == "door-store") {
        IntegrationDelivery.toggleAddresVisibility(true)
    } else {
        IntegrationDelivery.toggleAddresVisibility(false)
    }
    b();
    $(g + "receiverCityName").parent().delegate("input", "input", function() {
        $(g + "receiverCity").trigger("input")
    });
    $(g + "receiverCity").change(function(j, i) {
        if ($(g + "receiverCityName").val() == "") {
            return
        }
        if (i.label != undefined) {
            $("#intaro_crmbundle_ordertype_deliveryAddress_city").val(i.label)
        }
        if (i.region != undefined) {
            $("#region-text input").val(i.region);
            $("#integration-delivery__region-info span.static").text(i.region)
        }
    });
    var a = function() {
        if ($("#intaro-crm-deliveries").data("code") != "sdek") {
            return
        }
        d();
        IntegrationDelivery.dataIsChange(true)
    };
    $("#payments").bind("collection:changed", a);
    $(g + "receiverCity," + g + "payerType,#intaro_crmbundle_ordertype_shipmentStore,#intaro_crmbundle_ordertype_weight,#intaro_crmbundle_ordertype_length,#intaro_crmbundle_ordertype_width,#intaro_crmbundle_ordertype_height").change(a);
    $("#intaro-crm-deliveries .sdek-deliveries-popup-open").click(function(j) {
        j.preventDefault();
        var i = function(n) {
            var m = $(".integration-delivery").data(g + "tariffType_prev");
            var l = $(".integration-delivery").data(g + "pickuppoint_prev");
            if (!m && !l) {
                return
            }
            var k = "tr";
            if (m) {
                k += '[data-tariff-id = "' + m + '"]'
            }
            if (l) {
                k += '[data-pvz-id = "' + l + '"]'
            }
            n.find(k).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), i)
    });
    IntegrationDelivery.deliveriesPopupFilter();
    $(g + "date").change(function() {
        $("#intaro_crmbundle_ordertype_deliveryDate").val($(this).val()).change();
        b()
    });
    if ($(g + "timeBegin").length != 0) {
        $(g + "timeBegin, " + g + "timeEnd").change(function() {
            var k = "";
            var j = $(g + "timeBegin").val();
            var i = $(g + "timeEnd").val();
            if (j != "") {
                k += j
            }
            if (i != "") {
                if (j == "") {
                    k += "00:00"
                }
                k += " - " + i
            }
            $("#intaro_crmbundle_ordertype_deliveryAddress_deliveryTime").val(k).change()
        })
    }
    IntegrationDelivery.initItemsDeclaredValuePopup();
    var e = new DeliveryPackages($("#deliveries-parcels-edit"), $("#deliveries-parcels"));
    e.init();
    $("#deliveries-parcels").bind("deliveryPackage.save", function() {
        IntegrationDelivery.dataIsChange(true);
        d();
        if (e.count == 0) {
            $(g + "packageNumber").parent().show();
            IntegrationDelivery.requiredFields = ["intaro_crmbundle_ordertype_weight", "intaro_crmbundle_ordertype_length", "intaro_crmbundle_ordertype_width", "intaro_crmbundle_ordertype_height"];
            IntegrationDelivery.updateRequiredFields(true)
        } else {
            $(g + "packageNumber").parent().hide();
            IntegrationDelivery.updateRequiredFields();
            IntegrationDelivery.requiredFields = [];
            IntegrationDelivery.updateRequiredFields(true)
        }
    });
    if ($("#deliveries-parcels").data("deliveryPackage").count > 0) {
        $(g + "packageNumber").parent().hide();
        IntegrationDelivery.requiredFields = []
    } else {
        IntegrationDelivery.requiredFields = ["intaro_crmbundle_ordertype_weight", "intaro_crmbundle_ordertype_length", "intaro_crmbundle_ordertype_width", "intaro_crmbundle_ordertype_height"];
        IntegrationDelivery.updateRequiredFields(true)
    }
    $("#available-deliveries").delegate(".modern-table tr td", "click", function() {
        d();
        var j = $("#available-deliveries");
        var o = $(this).parent();
        var p = ["direction", "delivery", "price", "pickuppoint"];
        data = {};
        for (var k in p) {
            $(g + p[k]).val(o.data(p[k].toLowerCase())).change()
        }
        if (o.data("id") !== "") {
            $(g + "pickuppoint").val(o.data("pvz-id"))
        }
        $(g + "tariffType").val(o.data("tariff-id"));
        $("#delivery-name").data("delivery-type", o.data("type"));
        var n = o.data("full-name");
        $("#delivery-name").text(n).parent().removeClass("hide");
        $(g + "deliveryName").val(n);
        var u = $.datepicker.formatDate(_getDateFormatString(), new Date(o.data("date-min"))) + " - " + $.datepicker.formatDate(_getDateFormatString(), new Date(o.data("date-max")));
        $("#sdek-delivery-date").text(u).parent().removeClass("hide");
        $(g + "days").val(u);
        $(g + "date").datepicker("option", {
            minDate: new Date(o.data("date-min"))
        }).change();
        $("#sdek-delivery-price").html(priceFormat(o.data("price"))).parent().removeClass("hide");
        $(g + "price").val(o.data("price"));
        Order.setDeliveryNetCost(o.data("net-cost"));
        Order.setDeliveryCost(o.data("price-by-currency"));
        var t = o.data("cash-on-delivery");
        if (t !== "") {
            t = parseInt(t);
            var m = '<div class="input-group cleared both" id="integration-delivery-cash-limit">                    <label class="label-common">' + _translate("info.cash_limit") + '</label>                        <div class="gray-bg red bold fs13">                          <span class="value">' + priceFormat(t) + "                          </span>                        </span>                    </span>                </div>";
            $("#delivery-name").parent().after(m);
            var r = parseFloat($("#order-total-summ .value").text().replace(/[^\d,]/, "").replace(/,/, "."));
            if (r >= t) {
                IntegrationDelivery.toggleCashPayment($(g + "deliveryName").data("cash-map"), true)
            }
        }
        if (o.data("type") === "store-store" || o.data("type") === "door-store") {
            var s = o.data("address");
            var i = o.data("note");
            $("#sdek-delivery-address").text(s).parent().removeClass("hide");
            $(g + "pointAddress").val(s);
            if (i != "") {
                $("#sdek-delivery-instruction").text(i).parent().removeClass("hide");
                $(g + "instruction").val(i)
            }
            var l = o.data("work-time");
            if (l != "") {
                $("#sdek-delivery-schedules").text(l).parent().removeClass("hide");
                $(g + "schedules").val(l)
            }
            var q = o.data("phone");
            if (q) {
                $("#sdek-delivery-phone").text(q).parent().removeClass("hide");
                $(g + "phone").val(q)
            }
            IntegrationDelivery.toggleAddresVisibility(true)
        } else {
            IntegrationDelivery.toggleAddresVisibility(false)
        }
        IntegrationDelivery.saveFields();
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_contactPhone").val(q);
        j.find(".close").click()
    })
};
IntegrationDelivery.modules.spsr = function() {
    var g;
    var d = IntegrationDelivery.prefix;
    IntegrationDelivery.popupRequiredFields = function() {
        return {
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_deliveryAddress_city: "delivery_city",
            intaro_crmbundle_ordertype_integrationDeliveryData_encloseType: "content_type",
            intaro_crmbundle_ordertype_weight: "weight"
        }
    };
    var c = function(k) {
        if (typeof(k) != "undefined") {
            k.abort()
        }
        if ($("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryCode").val() == "") {
            return
        }
        var i = $("#intaro-crm-deliveries").data("dictionary-url");
        if (!$("#integration-delivery-cost").parent().find(".small-static-loader").length) {
            var h = '<span class="small-static-loader"></span>';
            $("#integration-delivery-cost").parent().append(h)
        }
        var j = new Object();
        j.type = "calc";
        j.storeId = $("#intaro_crmbundle_ordertype_shipmentStore").val();
        j.city = $("#intaro_crmbundle_ordertype_integrationDeliveryData_city").val();
        j.encloseType = $("#intaro_crmbundle_ordertype_integrationDeliveryData_encloseType").val();
        j.weight = $("#intaro_crmbundle_ordertype_weight").val();
        j.amount = parseInt($("#order-product-summ .value").text().replace(/\s/gi, ""));
        if ($("#integration-delivery-cost").text() == "") {
            return false
        }
        j.insuranceType = $("#intaro_crmbundle_ordertype_integrationDeliveryData_insuranceType option:selected").val();
        j.deliveryCode = $("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryCode").val();
        j.services = $("#delivery-extra-services :checked").map(function() {
            return this.value
        }).get();
        k = $.post(i, j).success(function(l) {
            $("#delivery-net-cost").val(l.totalPrice).change();
            $("#integration-delivery-cost").html(priceFormat(l.totalPrice)).parent().removeClass("hide");
            Order.setDeliveryCost(l.totalPrice)
        }).always(function() {
            $("#integration-delivery-cost").parent().find(".small-static-loader").remove()
        });
        return k
    };
    var b = function(j) {
        var i = {
            intaro_crmbundle_ordertype_deliveryAddress_region: "region",
            intaro_crmbundle_ordertype_deliveryAddress_city: "city",
            intaro_crmbundle_ordertype_deliveryAddress_street: "street",
            intaro_crmbundle_ordertype_deliveryAddress_building: "building"
        };
        if (!IntegrationDelivery.validateDeliveriesInfo(j, i)) {
            return
        }
        var l = $("#available-quotes");
        var h = $("#intaro-crm-deliveries").data("dictionary-url");
        var k = new Object();
        k.type = "quotes";
        k.region = $("#region-text input").val();
        k.city = $("#city-text input").val();
        k.street = $("#street-text input").val();
        k.building = $("#building-text input").val();
        l.intaroPopup({
            url: h,
            type: "POST",
            data: k,
            onError: function(m) {
                alert(_translate("alert.load_quota_list_error"))
            }
        });
        return false
    };
    IntegrationDelivery.saveFields();
    var f = function() {
        if (!$("#intaro-crm-deliveries").data("editable")) {
            return
        }
        IntegrationDelivery.saveFields();
        $("#integration-delivery-cost,            #integration-delivery-date,            #integration-delivery-status,            #integration-delivery-id").text("").parent().addClass("hide");
        $("#delivery-name").text(_translate("info.no_select_mas"));
        $(d + "deliveryCode,  " + d + "days,  " + d + "price").val("");
        Order.setDeliveryCost(0);
        $("#intaro_crmbundle_ordertype_deliveryDate").datepicker("option", {
            minDate: 0
        }).change()
    };
    $("#intaro-crm-deliveries .spsr-deliveries-popup-open").click(function(i) {
        i.preventDefault();
        var h = function(l) {
            var k = $(".integration-delivery").data(d + "deliveryCode_prev");
            if (!k) {
                return
            }
            var j = "tr";
            if (k) {
                j += '[data-code = "' + k + '"]'
            }
            l.find(j).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), h)
    });
    $("#intaro-crm-deliveries .quotes-popup-open").click(function() {
        b($(this))
    });
    enableAutocomplete();
    if ($(d + "city").val() != "") {
        var a = $("#city-text input").val();
        $(d + "city").prev("input").val(a)
    } else {
        if ($("#city-text input").val() != "") {
            invokeAutocompleteWithTerm($(d + "city").prev(".autocomplete"), $("#delivery-address-form").data("addressForm").getCity(false))
        }
    }
    var e = function() {
        if ($("#city-text input").val() != "") {
            invokeAutocompleteWithTerm($(d + "city").prev(".autocomplete"), $("#delivery-address-form").data("addressForm").getCity(false))
        }
    };
    $("#parse-address-btn").bind("addressParsed", e);
    $(d + "deliveryCode").change(function() {
        if ($(this).val() != "") {
            if ($("#delivery-name").data("services") != undefined) {
                $("#delivery-extra-services input:checkbox").each(function() {
                    if ($("#delivery-name").data("services").indexOf($(this).val()) == -1) {
                        $(this).attr("checked", false).attr("disabled", true)
                    } else {
                        $(this).attr("disabled", false)
                    }
                })
            }
        }
    }).change();
    $(d + "encloseType," + d + "city," + d + "declaredValue,#intaro_crmbundle_ordertype_shipmentStore,#intaro_crmbundle_ordertype_weight,#intaro_crmbundle_ordertype_length,#intaro_crmbundle_ordertype_width,#intaro_crmbundle_ordertype_height").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "spsr") {
            return
        }
        IntegrationDelivery.dataIsChange(true);
        f()
    });
    $(d + "city").change(function(i, h) {
        $cityData = $(this).prev("input").val();
        $("#intaro_crmbundle_ordertype_deliveryAddress_city").val($cityData);
        if (h !== undefined && h.region !== undefined) {
            $("#intaro_crmbundle_ordertype_deliveryAddress_region").val(h.region)
        } else {
            $("#intaro_crmbundle_ordertype_deliveryAddress_region").val("")
        }
    });
    $(d + "time").change(function() {
        $("#intaro_crmbundle_ordertype_deliveryAddress_deliveryTime").val($(this).find("option:selected").text())
    });
    $("#delivery-cost, #delivery-net-cost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "spsr") {
            return
        }
        if ($('#intaro-crm-deliveries[data-code="spsr"]').length && $('#intaro-crm-deliveries input:checkbox[value="PaidByReceiver"]:checked').length) {
            $(this).data("previous-cost", $(this).val());
            Order.updateDeliveryCost($(this), priceFormat(0, undefined, true));
            recalcTotalSumm()
        }
    });
    $('#intaro-crm-deliveries input:checkbox[value="PaidByReceiver"]').change(function() {
        if ($(this).is(":checked")) {
            $("#delivery-cost").change();
            $("#delivery-net-cost").change()
        } else {
            if ($("#delivery-cost").data("previous-cost") != undefined) {
                $("#delivery-cost").val($("#delivery-cost").data("previous-cost")).change();
                $("#delivery-net-cost").val($("#delivery-net-cost").data("previous-cost")).change()
            }
        }
    }).change();
    $("#delivery-extra-services input," + d + "insuranceType").change(function() {
        IntegrationDelivery.dataIsChange(true);
        g = c(g)
    });
    $("a.spsr-get-barcode").click(function(i) {
        i.preventDefault();
        var h = '<span class="small-static-loader"></span>';
        $input = $(d + "pieceId");
        $input.after(h);
        $.get($input.data("url")).success(function(l) {
            $input.parent().find(".small-static-loader").remove();
            if (l.error != undefined && l.error != "") {
                var j = $input.parents(".input-group");
                var k = $("<div></div>");
                k.addClass("msg-error script-error").attr("id", "script_error_pieceId").text(l.error);
                j.prepend(k);
                $input.one("input", function() {
                    $("#script_error_pieceId").remove()
                });
                $("a.spsr-get-barcode").one("click", function() {
                    $("#script_error_pieceId").remove()
                });
                return
            }
            if (l.barcode != "") {
                $input.val(l.barcode)
            }
        })
    });
    $("#available-deliveries").delegate(".modern-table tr td", "click", function() {
        f();
        var m = $("#available-deliveries");
        var k = $(this).parent();
        var h = ["days", "price"];
        for (var l in h) {
            $(d + h[l]).val(k.data(h[l])).change()
        }
        $("#delivery-name").text(k.data("name")).data("services", k.data("services")).parent().removeClass("hide");
        $(d + "deliveryName").val(k.data("name")).change();
        $(d + "deliveryCode").val(k.data("code")).change();
        var j = k.data("total-price");
        $("#integration-delivery-cost").html(priceFormat(j)).parent().removeClass("hide");
        $(d + "price").val(j);
        $("#delivery-net-cost").val(j).change();
        Order.setDeliveryCost(j);
        var n = k.data("days") + " " + _translate("measure.day.cont");
        $("#integration-delivery-date").text(n).parent().removeClass("hide");
        var i = parseInt(k.data("days").split("-")[0]);
        if (!isNaN(i)) {
            $("#intaro_crmbundle_ordertype_deliveryDate").datepicker("option", {
                minDate: i
            }).change()
        }
        m.find(".close").click()
    });
    $("#available-quotes").delegate(".modern-table tr td", "click", function() {
        var l = $("#available-quotes");
        var j = $(this).parent();
        if ($("#quote-start").val() == "" || $("#quote-end").val() == "") {
            $("#quote-start").parent().find(".script-error").remove();
            var h = $("<div></div>");
            h.addClass("msg-error script-error").attr("id", "script_error_quote-start").text(_translate("message.need_set_delivery_time"));
            $("#quote-start").parent().prepend(h);
            return false
        }
        var k = new Object();
        k.action = "create";
        k.externalId = $(d + "externalId").val();
        k.date = j.data("date");
        k.zone = j.data("zone");
        k.streetId = j.data("street-id");
        k.streetOwnerId = j.data("street-owner-id");
        k.house = $("#building-text input").val();
        k.time = $("#quote-start").val().split(":")[0] + "-" + $("#quote-end").val().split(":")[0];
        l.find(".loader").removeClass("hide");
        l.find(".stat-content").css("opacity", 0.5);
        var i = $("#available-quotes").data("url");
        $.post(i, k).success(function(m) {
            if (m.success) {
                $("#spsr-quote-text > span").text(k.date + " " + $("#quote-start").val() + "-" + $("#quote-end").val());
                $(".quotes-popup-open").addClass("hide");
                $(".quotes-popup-open").next().removeClass("hide")
            }
            l.find(".close").click()
        }).always(function() {
            l.find(".stat-content").css("opacity", 1);
            l.find(".loader").addClass("hide")
        })
    });
    $(".quotes-delete").click(function() {
        var i = $(this);
        i.find(".loading").removeClass("hide");
        var h = $("#available-quotes").data("url");
        var j = new Object();
        j.action = "delete";
        j.externalId = $(d + "externalId").val();
        $.post(h, j).success(function(k) {
            if (k.success) {
                $("#spsr-quote-text > span").text(_translate("info.no_reserve_fem"));
                i.addClass("hide");
                i.prev().removeClass("hide")
            }
        }).always(function() {
            i.find(".loading").addClass("hide")
        })
    });
    $("#intaro_crmbundle_ordertype_deliveryType").one("change", function() {
        $("#intaro-crm-deliveries .deliveries-popup-open").unbind("click");
        $("#parse-address-btn").unbind("addressParsed", e)
    })
};
IntegrationDelivery.modules.yandex = function() {
    var d = IntegrationDelivery.prefix;
    IntegrationDelivery.popupRequiredFields = function() {
        return {
            intaro_crmbundle_ordertype_shipmentStore: "store",
            intaro_crmbundle_ordertype_weight: "weight",
            intaro_crmbundle_ordertype_length: "length",
            intaro_crmbundle_ordertype_width: "width",
            intaro_crmbundle_ordertype_height: "height",
            intaro_crmbundle_ordertype_paymentType: "payment_type",
            intaro_crmbundle_ordertype_deliveryAddress_city: "delivery_city"
        }
    };
    $("#intaro-crm-deliveries .yandex-deliveries-popup-open").click(function() {
        var f = function(i) {
            var h = $(d + "pickuppoint").val();
            if (!h) {
                return
            }
            var g = "tr";
            if (h) {
                g += '[data-pickuppoint = "' + h + '"]'
            }
            i.find(g).addClass("important-auto-data")
        };
        IntegrationDelivery.openDeliveriesPopup($(this), f);
        return false
    });
    IntegrationDelivery.dataIsChange(true);
    IntegrationDelivery.deliveriesPopupFilter();
    var e = function(i, f) {
        var h = "#intaro_crmbundle_ordertype_";
        var g = "integrationDeliveryData_";
        if ("undefined" === typeof i) {
            return
        }
        if ("undefined" === typeof f || isNaN(f)) {
            f = i
        }
        Order.setDeliveryNetCost(f);
        Order.setDeliveryCost(i)
    };
    var c = function() {
        var h = parseInt(parseFloatText($("#order-total-summ .value").text()));
        if (isNaN(h)) {
            return
        }
        var g = $("#intaro_crmbundle_ordertype_integrationDeliveryData_assessedValue");
        if (g.hasClass("modified")) {
            return
        }
        var f = parseInt(g.val());
        if (h != f) {
            $("#intaro_crmbundle_ordertype_integrationDeliveryData_assessedValue").val(h).change()
        }
    };
    var b = function(f) {
        $.when(IntegrationDelivery.toggleAddresVisibility(f)).then(function() {
            $("#city-text").show()
        });
        IntegrationDelivery.updateRequiredFields(true, "#delivery-address-form")
    };
    var a = function() {
        if ($("#intaro-crm-deliveries").data("code") != "yandex") {
            return
        }
        e()
    };
    $("#payments").bind("collection:changed", a);
    $("#intaro_crmbundle_ordertype_site").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "yandex") {
            return
        }
        var g = $(".integration-delivery");
        var h = g.data("store-mapping");
        var j = [];
        if (h[$(this).val()] != undefined) {
            for (var f = 0; f < h[$(this).val()].length; f++) {
                j.push(h[$(this).val()][f])
            }
        }
        g.data("available-stores", j);
        Order.triggerEvent("shipmentStore:available")
    }).change();
    $(d + "confirm").change(function() {
        if ($(this).is(":checked")) {
            toggleInputRequired($(d + "shipmentType"), true);
            toggleInputRequired($("#intaro_crmbundle_ordertype_shipmentDate"), true)
        } else {
            toggleInputRequired($(d + "shipmentType"), false);
            toggleInputRequired($("#intaro_crmbundle_ordertype_shipmentDate"), false)
        }
    }).change();
    $("#available-deliveries").delegate(".modern-table tr td", "click", function() {
        $("#delivery-name,             #yandex-delivery-phone").parent().addClass("hide");
        var f = $("#available-deliveries");
        var l = $(this).parent();
        var m = ["direction", "tariffId", "tariffName", "pickuppoint"];
        var k = "intaro_crmbundle_ordertype_integrationDeliveryData_";
        data = {};
        for (var h in m) {
            $("#" + k + m[h]).val(l.data(m[h].toLowerCase())).change()
        }
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryExtId").val(l.data("delivery-id"));
        var j = l.data("name") + " (" + l.data("tariffname") + ")";
        $("#delivery-name").text(j).parent().removeClass("hide");
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryName").val(j);
        var p = l.data("days") + " " + _translate("measure.day.cont");
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_days").val(p);
        $("#yandex-delivery-date").text(l.data("delivery-date")).parent().removeClass("hide");
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryDate").val(l.data("delivery-date"));
        var i = parseInt(l.data("cost"));
        var g = parseInt(l.data("net-cost"));
        e(i, g);
        if (l.data("type") === "pickup" || l.data("type") === "post") {
            var o = l.data("address");
            $("#yandex-delivery-address").text(o).parent().removeClass("hide");
            $("#intaro_crmbundle_ordertype_integrationDeliveryData_pointAddress").val(o)
        } else {
            $("#intaro_crmbundle_ordertype_integrationDeliveryData_pointAddress").val("")
        }
        $("#" + k + "availableIntervals").val(l.attr("data-delivery-intervals")).change();
        var n = l.data("phone");
        if (n) {
            $("#yandex-delivery-phone").text(n).parent().removeClass("hide")
        }
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_contactPhone").val(n);
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_pickuppoint").change();
        f.find(".close").click()
    });
    $("#intaro_crmbundle_ordertype_deliveryCost").change(function() {
        IntegrationDelivery.dataIsChange(true)
    });
    $(d + "availableIntervals").change(function() {
        var f = $("#time-group");
        var g = f.find(".dropdown ul");
        g.children("li[data-yandex-id]").remove();
        var h = false;
        try {
            h = JSON.parse($(this).val())
        } catch (l) {}
        var i = false;
        if (h && $(d + "intervalId").val() != "") {
            for (var k in h) {
                if (h[k].id == $(d + "intervalId").val()) {
                    i = true;
                    break
                }
            }
        }
        if (!i) {
            $(d + "intervalId").val("");
            g.find("a.clear").click()
        }
        if (!h) {
            g.children("li:not([data-yandex-id])").show();
            return
        }
        g.children("li:not([data-yandex-id])").hide();
        for (var k in h) {
            var j = $("<a></a>");
            j.attr("class", "range").attr("href", "#").attr("data-from", h[k].from).attr("data-to", h[k].to).attr("data-title", "Range").text(h[k].text);
            var m = $("<li></li>");
            m.append(j).attr("data-yandex-id", h[k].id).click(function(n) {
                n.preventDefault();
                $("#intaro_crmbundle_ordertype_integrationDeliveryData_intervalId").val($(this).attr("data-yandex-id"))
            });
            g.append(m)
        }
    }).change();
    $("#intaro-crm-deliveries :input:not(" + d + "locked," + d + "confirm," + d + "shipmentType," + d + "pickuppoint," + d + "toYdWarehouse," + d + "parcelDate),#intaro_crmbundle_ordertype_deliveryAddress_city,#dimension-block input,#intaro_crmbundle_ordertype_deliveryCost").change(function() {
        if ($("#intaro-crm-deliveries").data("code") != "yandex" || !$("#intaro-crm-deliveries").data("editable")) {
            return
        }
        IntegrationDelivery.dataIsChange(true);
        $("#intaro-crm-deliveries span.static").parent().addClass("hide");
        $("#delivery-name").html(_translate("info.no_select_fem")).parent().removeClass("hide");
        $("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryExtId").val(null);
        b(false)
    });
    b($("#intaro_crmbundle_ordertype_integrationDeliveryData_pickuppoint").val());
    $("#intaro_crmbundle_ordertype_integrationDeliveryData_pickuppoint").change(function() {
        b($("#intaro_crmbundle_ordertype_integrationDeliveryData_pickuppoint").val())
    });
    $("#section-stores select[data-cities]").change(function() {
        var f = $(this).parents("td").next().find("input");
        var h = parseInt($(this).val());
        var g = $(this).data("cities");
        if ("undefined" !== typeof g[h]) {
            f.val(g[h])
        } else {
            f.val("")
        }
    });
    $("#order-product-summ").bind("totalProductSummUpdated", c);
    if (!$("#intaro_crmbundle_ordertype_integrationDeliveryData_deliveryExtId").val()) {
        c()
    }
    $("#intaro_crmbundle_ordertype_integrationDeliveryData_assessedValue").bind("input", function() {
        $(this).addClass("modified")
    });
    $("#intaro_crmbundle_ordertype_deliveryType").one("change", function() {
        $("#intaro_crmbundle_ordertype_site, #intaro_crmbundle_ordertype_deliveryAddress_city").unbind("change");
        $("#payments").unbind("collection:changed", a);
        $("#intaro-crm-deliveries .deliveries-popup-open").unbind("click");
        var f = $("#time-group");
        f.find(".dropdown ul li[data-yandex-id]").remove();
        f.find(".dropdown ul li").show()
    })
};

function IntegrationMarketplaceData() {
    this.prefix = "#intaro_crmbundle_ordertype_integrationMarketplaceData_";
    this.$dataContainer = $("#integration-marketplace-data");
    this.marketplaceCode;
    this.init = function() {
        if (this.$dataContainer.length == 0 || this.$dataContainer.data("code") == undefined) {
            return
        }
        this.marketplaceCode = this.$dataContainer.data("code");
        if (this.marketplaceCode == "yandex") {
            this.yandexLogick()
        }
    };
    this.yandexLogick = function() {
        var a = this;
        var b = function() {
            var d = $("#intaro_crmbundle_ordertype_deliveryType").val();
            var e = a.$dataContainer.data("delivery-type-mapping");
            if (d == "" || e[d] == undefined || e[d]["yandexType"] != "PICKUP") {
                a.$dataContainer.hide();
                toggleInputRequired($(a.prefix + "pickuppointName"), false)
            } else {
                a.$dataContainer.show();
                toggleInputRequired($(a.prefix + "pickuppointName"), true)
            }
        };
        var c = function(h, i) {
            var f = $(a.prefix + "pickuppointId");
            var g = $(a.prefix + "pickuppointAddress");
            var e = $(a.prefix + "pickuppointPhone");
            var j = $(a.prefix + "pickuppointSchedule");
            var d = $(a.prefix + "pickuppointType");
            if (f.val() == "") {
                g.val("").change();
                e.val("").change();
                j.val("").change();
                d.val("").change()
            } else {
                if (typeof i != "undefined") {
                    if (i.address != "") {
                        g.val(i.address).change()
                    }
                    if (i.phone != "") {
                        e.val(i.phone).change()
                    }
                    if (i.schedule != "") {
                        j.val(i.schedule).change()
                    }
                    if (i.type != "") {
                        d.val(i.type).change()
                    }
                }
            }
        };
        $(a.prefix + "pickuppointAddress," + a.prefix + "pickuppointPhone," + a.prefix + "pickuppointSchedule").change(function() {
            if ($(this).val() == "") {
                $(this).siblings("span.static").text(_translate("info.no_select_fem")).parent().hide()
            } else {
                $(this).siblings("span.static").text($(this).val()).parent().show()
            }
        });
        $("#intaro_crmbundle_ordertype_deliveryType").change(b);
        b();
        $(a.prefix + "pickuppointId").change(c);
        c()
    }
};
$(function() {
    defineCommunicationEvents();
    initMessageForm("#message-send-form");
    initEditor(document);
    if ($(".add-twig-type").length) {
        swapTwigFields($(".add-twig-type"))
    }
    $(".stat-box-content .letter-answer").live("click", function() {
        var a = $(this).data("reply-href");
        if (!a) {
            return false
        }
        $(this).parents(".stat-box-popup").find(".close").click();
        popupOpenByUrl(a);
        return false
    });
    $('[name="intaro_crmmessagebundle_letter_type[template]"]').live("change", function() {
        var h = $(this);
        var a = h.closest("form");
        var b = h.val();
        if (b) {
            var e = a.find('[name="intaro_crmmessagebundle_letter_type[templateBody]"]');
            var d = a.find('[name="intaro_crmmessagebundle_letter_type[senderString]"]');
            var l = a.find('[name="intaro_crmmessagebundle_letter_type[orderPlate]"]');
            var f = a.find('[name="intaro_crmmessagebundle_letter_type[theme]"]');
            var j = a.find('[name="intaro_crmmessagebundle_letter_type[recipient]"]');
            var k = a.find('[name="intaro_crmmessagebundle_letter_type[title]"]');
            var i = a.find(".attachments-widget");
            var c = a.find(".small-text-editor");
            if (!d.attr("disabled") && d.val() || f.val() || j.val() || e.val() || l.val()) {
                var g = "";
                if (!d.attr("disabled")) {
                    g = "«" + _translate("info.from_who") + "», "
                }
                if (l.length) {
                    g = "«" + _translate("info.plate") + "», "
                }
                g += _translate("info.subject_recipient_and_template");
                if (!confirm(_translate("alert.replace_fields_by_template", {
                        fields: g
                    }))) {
                    return false
                }
            }
            $.ajax({
                url: $(this).attr("data-action").replace("___", b),
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    h.parent().find(".small-loader").show()
                },
                complete: function() {
                    h.parent().find(".small-loader").hide()
                },
                success: function(n) {
                    if (!d.attr("disabled") && typeof n.sender !== "undefined" && n.sender.length) {
                        d.val(n.sender)
                    }
                    k.val(n.title);
                    f.val(n.theme);
                    j.val(n.recipient);
                    e.val(n.template);
                    if (typeof n.order_plate !== "undefined") {
                        l.val(n.order_plate.id);
                        l.trigger("chosen:updated")
                    }
                    FormAttachmentHandler.addToList(i, n.attachments, attachments_path);
                    if (typeof(ace) !== "undefined" && c.length) {
                        var m = ace.edit(c.attr("id"));
                        m.session.setValue(n.template)
                    }
                },
                error: function(m) {
                    alert(CRM_ERROR_MESSAGE)
                }
            })
        }
    });
    $('[name="intaro_crmmessagebundle_sms_type[template]"]').live("change", function() {
        var b = $(this);
        var d = b.closest("form");
        var c = b.val();
        if (c) {
            var g = d.find('[name="intaro_crmmessagebundle_sms_type[templateBody]"]');
            var e = d.find('[name="intaro_crmmessagebundle_sms_type[recipient]"]');
            var f = d.find('[name="intaro_crmmessagebundle_sms_type[title]"]');
            var a = d.find(".small-text-editor");
            if (g.val()) {
                if (!confirm(_translate("confirm.set_text_template"))) {
                    return false
                }
            }
            $.ajax({
                url: $(this).attr("data-action").replace("___", c),
                type: "GET",
                dataType: "json",
                beforeSend: function() {
                    b.parent().find(".small-loader").show()
                },
                complete: function() {
                    b.parent().find(".small-loader").hide()
                },
                success: function(i) {
                    f.val(i.title);
                    e.val(i.recipient);
                    if (typeof(ace) !== "undefined" && a.length) {
                        var h = ace.edit(a.attr("id"));
                        h.session.setValue(i.template)
                    }
                },
                error: function(h) {
                    alert(CRM_ERROR_MESSAGE)
                }
            })
        }
    });
    $(".add-twig-type").live("change", function() {
        swapTwigFields($(this))
    });
    $(".insert-twig-tag").live("click", function() {
        var a = $(".named-tag:visible .add-twig-tag").val();
        if (a != "") {
            if (typeof(tinymce) != "undefined" && typeof(tinymce.activeEditor) != "undefined") {
                tinymce.activeEditor.execCommand("mceInsertContent", false, "{{ " + a + " }}")
            } else {
                if (typeof(ace) != "undefined") {
                    var b = ace.edit($(".small-text-editor").attr("id"));
                    b.insert("{{ " + a + " }}")
                }
            }
        }
    });
    if (typeof verificationSettings != "undefined") {
        $("input[type=email].check-verification").each(function() {
            var b = $(this);
            if (!$.trim(b.val()).length) {
                return
            }
            var a = $('<div class="small-loader input l"></div>').insertAfter(b);
            $.ajax({
                url: verificationSettings.checkPath,
                data: {
                    email: $.trim(b.val())
                },
                type: "POST",
                dataType: "json",
                error: function() {
                    alert(CRM_ERROR_MESSAGE);
                    a.remove()
                },
                success: function(c) {
                    a.remove();
                    $(c.content).insertAfter(b.parents(".controls"))
                }
            })
        })
    }
    $(".msg-preview .preview-btn").live("click", function(d) {
        d.preventDefault();
        var c = $(this),
            a = c.parents("form");
        if (typeof tinyMCE !== "undefined") {
            tinyMCE.triggerSave()
        }
        if (a.length) {
            var b = new FormData(a[0]);
            previewMessage(c.attr("href"), b, a)
        }
    });
    $(".preview-close").live("click", function(a) {
        a.preventDefault();
        $(this).closest(".preview-text").hide()
    });
    $("#intaro_crmmessagebundle_letter_template_type_sender").bind("keyup change", function() {
        var a = $(this).val();
        if (checkEmailDomain(a) === false) {
            var b = a.substr(a.indexOf("@") + 1).replace(">", "");
            $(this).nextAll(".help-info").text(_translate("info.email_template_sender_vetification", {
                domain: b
            })).removeClass("hide")
        } else {
            $(this).nextAll(".help-info").addClass("hide")
        }
    });
    $("#letter-template-set-sender").click(function(a) {
        a.preventDefault();
        $(".sender-control").removeClass("hide");
        $(this).parents(".control-group").hide()
    })
});
previewMessage = function(a, b, c) {
    $.ajax({
        url: a,
        type: "POST",
        data: b,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
            $(".preview-body", c).hide();
            $(".preview-text .loader", c).show()
        },
        complete: function() {
            $(".preview-text .loader", c).hide()
        },
        error: function() {
            alert(CRM_ERROR_MESSAGE)
        },
        success: function(e) {
            $("#section-template .msg-error", c).remove();
            if ($(e).hasClass("msg-error")) {
                $("#section-template", c).prepend(e);
                $(".preview-text", c).hide()
            } else {
                if ($("#preview-frame", c).length) {
                    var d = $("#preview-frame", c)[0];
                    var f = d.document;
                    if (d.contentDocument) {
                        f = d.contentDocument
                    } else {
                        if (d.contentWindow) {
                            f = d.contentWindow.document
                        }
                    }
                    f.open();
                    f.write(e);
                    f.close()
                } else {
                    $(".preview-body", c).html(e)
                }
                $(".preview-body", c).show()
            }
        }
    });
    $("#preview-frame", c).contents().find("body").html("");
    $(".preview-text", c).show()
};

function bindSms() {
    $(".sms-list a.sms-item").click(onPopupOpen)
}

function bindLetters() {
    $(".letters-list a.letter-item").click(onPopupOpen)
}

function defineCommunicationEvents() {
    bindLetters();
    bindSms();
    $("#new-sms").live("click", onPopupOpen);
    $("#new-letter").live("click", onPopupOpen);
    $("#new-support-letter").live("click", function(b) {
        b.preventDefault();
        $(this).parent().parent().hide();
        var a = $(this).data("href");
        popupOpenByUrl(a, $(this).data("popup"));
        return false
    });
    $("#support-send-form").live("submit", sendSupportLetter)
}

function initEditor(d) {
    d = $(d);
    var b = d.find("textarea.ace-editor");
    var e = d.find("textarea.tinymce");
    var a = d.find("#tiny_inner_image");
    var c = d.find("#tinymce_file_uploader");
    if (typeof(ace) !== "undefined" && b.length) {
        initAceEditor(b)
    }
    if (typeof window.initTinyMCE !== "undefined" && e.length) {
        initTinyMCE({
            selector: e
        })
    }
    if (a.length) {
        a.change(function() {
            $.ajax({
                url: c.attr("action"),
                type: "POST",
                data: new FormData(c[0]),
                contentType: false,
                processData: false,
                dataType: "json",
                success: function(f) {
                    if (f.error) {
                        alert(f.error);
                        return
                    }
                    if (typeof tinymce !== "undefined") {
                        tinymce.activeEditor.selection.setContent(f.content)
                    }
                },
                error: function() {
                    alert(CRM_ERROR_MESSAGE)
                }
            })
        })
    }
}

function initMessageForm(f, b) {
    b = b || {};
    f = $(f);
    var e = f.is('[name="message-send-form"]') ? f : f.find('[name="message-send-form"]');
    var a = $("#independent-popup-bk");
    var d = f.find(".ajax-progress");
    var c = e.find("button");
    initEditor(f);
    e.submit(function(g) {
        c.attr("disabled", "disabled");
        if (typeof tinyMCE !== "undefined") {
            tinyMCE.triggerSave()
        }
        $.ajax({
            url: e.attr("action"),
            type: "POST",
            data: new FormData(e[0]),
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function() {
                d.css("display", "inline-block")
            },
            complete: function() {
                d.hide()
            },
            error: function() {
                alert(CRM_ERROR_MESSAGE)
            },
            success: b.onSuccess || function(h) {
                e.trigger("message_form:submit", [h]);
                if (h.error) {
                    alert(h.error);
                    return
                }
                if (h.status) {
                    a.intaroPopup("close");
                    if (e.attr("action").match(/sms/)) {
                        $(document).trigger("sms:sent")
                    } else {
                        $(document).trigger("letter:sent")
                    }
                } else {
                    if (h.content) {
                        a.intaroPopup("setContent", h.content);
                        initMessageForm(a)
                    } else {
                        if (typeof h === "string") {
                            a.intaroPopup("setContent", h);
                            initMessageForm(a)
                        } else {
                            e.find(".msg-error").remove();
                            if (h.errors) {
                                $.each(h.errors, function(i, j) {
                                    var k = e.find('[name="' + i + '"]');
                                    if (!k.length) {
                                        return
                                    }
                                    var l = $("<ul></ul>").addClass("msg-error");
                                    l.insertAfter(k);
                                    $.each(j, function(n, m) {
                                        $("<li></li>").addClass("msg-error__item").text(m).appendTo(l)
                                    })
                                })
                            }
                            c.prop("disabled", false)
                        }
                    }
                }
            }
        });
        return false
    })
}
$(document).bind("sms:sent", function(c, b) {
    var a = $("#sms-data");
    if (!a.length) {
        return
    }
    a.animate({
        opacity: 0.5
    });
    loadOrderMessageList(a.data("url"), function(d) {
        a.replaceWith(d);
        a.find("td:not(.no-click)").click(function() {
            $(this).parent().find("a:first").trigger("click")
        });
        bindSms()
    })
});
$(document).bind("letter:sent", function(c, b) {
    var a = $("#letters-data");
    if (!a.length) {
        return
    }
    a.animate({
        opacity: 0.5
    });
    loadOrderMessageList(a.data("url"), function(d) {
        a.replaceWith(d);
        a.find("td:not(.no-click)").click(function() {
            $(this).parent().find("a:first").trigger("click")
        });
        bindLetters()
    })
});
var loadOrderMessageList = function(b, a) {
    $.ajax({
        url: b,
        type: "GET",
        error: function() {
            alert(CRM_ERROR_MESSAGE)
        },
        success: function(c) {
            if (typeof a === "function") {
                a.call(b, c)
            }
        }
    })
};

function tinymce_button_image_uploader(a) {
    $("#tinymce_file_uploader input[type=file]").click();
    return false
}

function swapTwigFields(f) {
    var e = f.val();
    var h = "",
        g = false;
    if (eventsContext.events[e].length > 0) {
        for (var d = 0; d < eventsContext.events[e].length; d++) {
            h += "<li>";
            h += eventsContext.events[e][d].title + " &mdash; <code>" + eventsContext.events[e][d].code + "</code>";
            if (eventsContext.events[e][d].entity) {
                h += " (" + _translate("info.type") + " <code>" + eventsContext.events[e][d].entity + "</code>)"
            }
            h += "</li>";
            if (eventsContext.events[e][d].code == "order") {
                g = true
            }
        }
    } else {
        h += "<li>" + _translate("info.none") + "</li>"
    }
    $("ul.event-variables").html(h);
    if (g) {
        $("#section-order-plate").show()
    } else {
        $("#section-order-plate").hide()
    }
    var a = "<option></option>";
    for (var d = 0; d < eventsContext.events[e].length; d++) {
        var c = eventsContext.events[e][d].code;
        if (typeof eventsContext.rules[c] !== "undefined") {
            a += '<optgroup label="' + eventsContext.events[e][d].title + '">';
            for (var b = 0; b < eventsContext.rules[c].length; b++) {
                a += '<option value="' + c + "." + eventsContext.rules[c][b].code + '">';
                a += eventsContext.rules[c][b].title + "</option>"
            }
            a += "</optgroup>"
        }
    }
    $(".named-tag select").html(a).trigger("chosen:updated");
    if (!f.is(":disabled")) {
        $("input.recipient-input").val(templateRecipients[e])
    }
    return false
}

function sendSupportLetter() {
    var b = $(this),
        c = new FormData(document.getElementById("support-send-form")),
        a = b.parents(".stat-box-popup");
    $('input[type="submit"]', b).attr("disabled", "disabled");
    $.ajax({
        url: b.attr("action"),
        type: "POST",
        data: c,
        processData: false,
        contentType: false,
        cache: false,
        beforeSend: function() {
            $(".ajax-progress", b).css("display", "inline-block")
        },
        complete: function() {
            $(".ajax-progress", b).hide()
        },
        error: function() {
            $('input[type="submit"]', b).removeAttr("disabled");
            alert(CRM_ERROR_MESSAGE)
        },
        success: function(d) {
            a.intaroPopup("setContent", d);
            initMessageForm(a)
        }
    });
    return false
}

function checkEmailDomain(b) {
    var e = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    if (b.length === 0) {
        return
    }
    var d = b.match(/<(.+?)>/);
    if (d && d.length == 2) {
        b = d[1]
    }
    if (!e.test(b)) {
        return
    }
    if (typeof verificationSettings == "undefined" || verificationSettings.domains == "undefined") {
        return
    }
    var c = b.substr(b.indexOf("@") + 1),
        a = JSON.parse(verificationSettings.domains);
    if (a.indexOf(c) == -1) {
        return false
    }
    return true
}(function() {
    $(".email-stat-handler").live("click", function(a) {
        a.preventDefault();
        $(this).toggleClass("open").siblings(".email-stat").slideToggle({
            duration: 300,
            easing: "linear"
        })
    })
})();
$(function() {
    FormAttachmentHandler.init();
    $("#attachments-popup-link").click(function(b) {
        b.preventDefault();
        var a = $("#" + $(this).data("popup"));
        a.bind("beforeOpen", function() {
            UploadAttachmentHandler.init($(this).find("#attach-file-input"));
            $("tr a.letter-ref", a).bind("click", function(d) {
                d.preventDefault();
                d.stopPropagation();
                var c = $("#independent-popup-bk");
                c.intaroPopup({
                    url: $(this).attr("href")
                });
                c.bind("intaroPopupClosed", function() {
                    $("#attachments-popup-link").trigger("click")
                })
            })
        });
        a.bind("beforeClose", function() {
            UploadAttachmentHandler.destroy($(this).find("#attach-file-input"));
            $("tr a.letter-ref", a).unbind("click");
            a.unbind("beforeOpen");
            a.unbind("beforeClose")
        });
        a.intaroPopup({
            url: $(this).data("href"),
            onError: function() {
                alert(_translate("alert.request_error_try_later"))
            }
        })
    })
});
var FormAttachmentHandler = (function() {
    var h = ".attachments-widget",
        f = ".add-another-file",
        b = ".delete-attachment",
        d = ".file-list";
    var c = function() {
        var j = $(this);
        if (j.val()) {
            j.parent().show()
        }
    };
    var a = function(n) {
        var m = $(this),
            l = m.data("prototype"),
            o = m.parents(h),
            j = $(d, o),
            k = m.data("init-counter");
        $fileInput = $(l.replace(/__name__/g, k));
        j.append($("<li></li>").hide().append($fileInput));
        m.data("init-counter", ++k);
        $fileInput.change(c);
        $fileInput.trigger("click");
        n.preventDefault()
    };
    var g = function(j) {
        j.preventDefault();
        if (confirm(_translate("confirm.delete_file"))) {
            $(this).closest("li").remove()
        }
    };
    var i = function() {
        if (!window.FormData) {
            $(f).hide();
            return
        }
        $(document).delegate(f, "click", a);
        $(document).delegate(b, "click", g)
    };
    var e = function(n, l, m) {
        if (typeof l === "undefined" && l.length === 0) {
            return
        }
        var j = $(d, n),
            k = j.data("files-prototype");
        if (!k) {
            return
        }
        j.find("li.saved").remove();
        $.each(l, function(p, r) {
            var q = r.file;
            if (j.find('a[href="' + m + q.filename + '"]').length) {
                return
            }
            var o = $(k.replace(/__name__/g, p).replace(/__filepath__/g, attachments_path + q.filename).replace(/__original_name__/g, typeof q.original_name !== "undefined" ? q.original_name : q.filename));
            o.find('input[name$="[id]"]').val(q.id);
            j.append(o)
        })
    };
    return {
        init: i,
        addToList: e
    }
})();
var UploadAttachmentHandler = (function() {
    var h = ".attachments-widget",
        m = ".attachment-dropzone",
        d = ".attachments-table",
        g = ".upload-another-file",
        b = "";
    var j = function(q) {
        var r = $(m),
            p = window.dropZoneTimeout;
        if (p) {
            clearTimeout(p)
        }
        var o = false,
            n = q.target;
        do {
            if (n === r[0]) {
                o = true;
                break
            }
            n = n.parentNode
        } while (n != null);
        if (o) {
            r.addClass("hover")
        } else {
            r.removeClass("hover")
        }
        window.dropZoneTimeout = setTimeout(function() {
            window.dropZoneTimeout = null;
            r.removeClass("hover")
        }, 300)
    };
    var f = function(p) {
        var n = $(d, p),
            o = n.find('tbody tr:not(".no-data-row")').length;
        if (o === 0) {
            n.find("tr.no-data-row").removeClass("hide")
        }
        $(b).text(o ? o : "")
    };
    var e = function(n) {
        return function(p) {
            var q = $(this).parent(),
                o = q.data("href");
            p.preventDefault();
            p.stopPropagation();
            q.html('<span class="red-loader"></span>');
            $.ajax({
                type: "GET",
                url: o,
                success: function() {
                    q.parent().remove();
                    f(n)
                }
            })
        }
    };
    var c = function(o, n) {
        return function(t, s) {
            var q = $(d, o),
                p = $(q.data("prototype")),
                u = p.clone(),
                r = s.files[0];
            $(".no-data-row", q).addClass("hide");
            $("td:last", u).prepend(r.name);
            $("tbody", q).prepend(u);
            s.context = u;
            s.submit();
            n.trigger("upload-attachment:upload", s)
        }
    };
    var a = function(o, n) {
        return function(r, q) {
            n.trigger("upload-attachment:done", q);
            var p = q.result;
            if (p.error) {
                q.context.remove();
                alert(p.error);
                return
            }
            q.context.replaceWith(p.content);
            f(o)
        }
    };
    var l = function(o, n) {
        return function(q, p) {
            n.trigger("upload-attachment:fail", p);
            alert(CRM_ERROR_MESSAGE);
            p.context.remove();
            f(o)
        }
    };
    var k = function(o) {
        b = o.data("counter-selector");
        var q = o.parents(h);
        var n = {
            replaceFileInput: false,
            sequentialUploads: true,
            add: c(q, o),
            done: a(q, o),
            fail: l(q, o)
        };
        if ($(m, q).length) {
            n.dropZone = $(m, q)
        }
        o.fileupload(n);
        var p = o.attr("id");
        if (p) {
            q.delegate(g + '[data-file-input="' + p + '"]', "click", function(r) {
                o.click();
                r.preventDefault()
            })
        }
        $(document).bind("dragover", j);
        q.delegate(".tr-delete", "click", e(q));
        q.delegate("tr a", "click", function(r) {
            r.stopPropagation()
        });
        q.delegate("tbody td", "click", function() {
            var r = $(this).parent(),
                s = r.find("a:first");
            if (s.length) {
                window.open(s.attr("href"), "_blank")
            }
        })
    };
    var i = function(n) {
        var p = n.parents(h);
        var o = n.attr("id");
        if (o) {
            p.undelegate(g + '[data-file-input="' + o + '"]', "click")
        }
        p.undelegate(".tr-delete", "click");
        p.undelegate("tr a", "click");
        p.undelegate("tbody td", "click")
    };
    return {
        init: k,
        destroy: i
    }
})();
$(function() {
    var a = $(".action-events");
    $(".timepicker").each(function() {
        var b = {},
            c = $(this).data("step");
        if (c) {
            b.step = c
        }
        $(this).timepicker(b)
    });
    $(".cause-event").change(function() {
        var l = $(this).val(),
            m = this,
            k = $(this);
        if (typeof rulesMap[l] == "undefined") {
            return false
        }
        var e = rulesMap[l]["events"],
            h = rulesMap[l]["fields"],
            b = rulesMap[l]["options"];
        $(".action-event").each(function() {
            var i = $(this).val(),
                c = $(this);
            if (i && typeof e[i] == "undefined") {
                alert(_translate("alert.action_not_allowed_for_event", {
                    action: $.trim(c.next().text()),
                    event: $.trim(k.find(":selected").text())
                }));
                k.val($.data(m, "val"));
                return false
            }
        });
        var g = $(".add-rule-action ul");
        g.children().remove();
        for (var d in e) {
            g.append('<li><a data-title="' + e[d] + '" data-value="' + d + '" href="#">' + e[d] + "</a></li>")
        }
        $(".optional").hide();
        $.each(b, function(p, c) {
            var o = $(".optional-" + p);
            o.find("label.control-label").html(c);
            o.show()
        });
        var j = $(".after-rule-choice");
        j.find("option").each(function() {
            var c = $(this);
            c.toggle($.inArray(l, c.data("type") || []) >= 0);
            j.trigger("chosen:updated")
        });
        if (j.find("option:selected").is(":hidden")) {
            j.find("option:visible:first").prop("selected", true);
            j.trigger("chosen:updated")
        }
        var n = "",
            f = 0;
        for (var d in h) {
            n += "<li>";
            n += h[d].title + " &ndash; <code>" + d + "</code>";
            n += h[d].entity ? " (" + _translate("info.type") + " <code>" + h[d].entity + "</code>)" : "";
            n += "</li>";
            f++
        }
        if (!f) {
            n += "<li>" + _translate("info.none") + "</li>"
        }
        $("ul.event-variables").html(n);
        $.data(m, "val", l)
    });
    a.data("index", a.find(".action-event-item").length);
    $(".cause-event").change();
    $(".dropdown.add-rule-action ul a").live("click", function() {
        var b = $(".cause-event").val();
        var d = $(this).data("value");
        var c = $(this).parent().parent().prev().addClass("loading");
        $.ajax({
            url: Routing.generate("crm_rules_action_event_form"),
            data: {
                action_event: d,
                cause_event: b
            },
            type: "POST",
            error: function() {
                alert(CRM_ERROR_MESSAGE)
            },
            success: function(h) {
                var e = a.data("index");
                var f = $(h.replace(/\_0\_/g, "_" + e + "_").replace(/\[0\]/g, "[" + e + "]"));
                a.data("index", e + 1);
                var g = a.append(f);
                var i = g.find("textarea.ace-editor");
                if (i.length) {
                    initAceEditor(i)
                }
                $(".timepicker").timepicker();
                g.initJsControls()
            },
            complete: function() {
                c.removeClass("loading")
            }
        });
        return false
    });
    $(".action-event-item .tr-delete").live("click", function() {
        if (confirm(_translate("confirm.delete_action"))) {
            $(this).parent().remove()
        }
        return false
    });
    $("#intaro_crmrulebundle_rule_useConversion").change(function() {
        $(".conversion-settings").toggleClass("hide")
    })
});
(function(b, c) {
    var e = function(i, h) {
        var g = $("#" + i),
            f = $("#" + i + "_result");
        g.change(function() {
            var j = h[g.val()];
            f.html(!j ? "" : "<code>" + j.split(/\|/).join("</code>, <code>") + "</code>")
        }).bind("keypress keyup keydown", function() {
            g.change()
        }).change()
    };
    var a = b[c] || [];
    b[c] = {
        push: function(f) {
            e(f[0], f[1])
        }
    };
    for (var d = 0; d < a.length; ++d) {
        b[c].push(a[d])
    }
})(window, "rmph");
var crmStoreReservePopup = {
    warningClass: "warning-circle-icon",
    popupId: "reserve-products",
    orderProductsTableId: "order-products-table",
    packRowClass: ".pack-row",
    trSelectedClass: "selected",
    reserveInputSelector: "#reserve-products .reserve input",
    excludeClickSelector: "input, .date-icon, .make-call, .warning-circle-icon",
    checkReserveCol: function(a) {
        if (a.is("td.reserve")) {
            return true
        }
        return false
    },
    showPopup: function(b) {
        var a = $("#" + this.popupId);
        this.setPopupHeaders();
        a.intaroPopup();
        this.sendReserve(b, false);
        a.find("input[name=reserve]").attr("disabled", "disabled");
        a.delegate("input[type=text], input[type=number]", "keypress", function(c) {
            if (c.which == 13) {
                a.find("input[name=reserve]").click()
            }
        })
    },
    viewWarning: function(b) {
        if (this.checkReserveCol(b)) {
            this.hideWarning();
            var a = this.getWarningHtml();
            a.appendTo(b)
        } else {
            b.after(this.getWarningHtml())
        }
    },
    hideWarning: function() {
        $("#" + this.popupId + " ." + this.warningClass).remove()
    },
    getWarningHtml: function() {
        var a = $("<span>");
        a.text("!").addClass(this.warningClass);
        return a
    },
    toInt: function(a) {
        a = parseInt(a);
        a = isNaN(a) ? 0 : a;
        return a
    },
    toFloat: function(a) {
        if ("string" === typeof a) {
            a = a.replace(",", ".")
        }
        a = parseFloat(a);
        a = isNaN(a) ? 0 : a;
        return a
    },
    sendReserve: function(f, g) {
        if ("undefined" === typeof g) {
            g = true
        }
        var e = $("#" + this.popupId);
        var c = {};
        if (g) {
            var b = $("#" + this.popupId + " .stat-content");
            c = b.wrap("<form>").parent().serializeArray();
            b.unwrap()
        }
        var a = e.data("url");
        a = a.replace(/0$/, f);
        var d = this;
        e.intaroPopup("updateContent", {
            url: a,
            type: g ? "POST" : "GET",
            dataType: "json",
            data: c,
            onSuccess: function(h) {
                if (g && "undefined" !== typeof h.productRow) {
                    if (h.orderStatusChanged) {
                        location.reload()
                    }
                    d.updateOrderProductRow(f, h.productRow);
                    e.intaroPopup("setContent", "");
                    e.intaroPopup("close");
                    $("#order-products-table .stores-th").prev().click().click()
                } else {
                    d.setPopupHeaders(h.name, h.quantity, h.totalReserved);
                    e.intaroPopup("setContent", h.tableContent);
                    e.data("order-product-id", f);
                    e.find(".datepicker").each(function() {
                        addDateIcon($(this))
                    }).datepicker({
                        dateFormat: _getDateFormatString()
                    })
                }
                e.find("input[name=reserve]").removeAttr("disabled");
                d.onPopupReady()
            }
        })
    },
    setPopupHeaders: function(a, b, c) {
        if ("undefined" === typeof a) {
            a = ""
        }
        if ("undefined" === typeof b) {
            b = "-"
        }
        if ("undefined" === typeof c) {
            c = "-"
        }
        var d = $("#" + this.popupId);
        d.find("h2 .main-info").text(a).attr("title", a);
        d.find(".popup-subhead .in-order > span").text(b);
        d.find(".popup-subhead .in-reserve > span").text(c).data("in-reserve", c)
    },
    getTotalInOrder: function() {
        var b = $("#" + this.popupId);
        var a = b.find(".popup-subhead .in-order > span").text();
        a = this.toFloat(a);
        return a
    },
    getTotalReserve: function() {
        var b = 0;
        var a = this;
        $("#" + this.popupId + " .reserve input").each(function() {
            b += a.toFloat($(this).val())
        });
        return b
    },
    getAvailableForTr: function(c) {
        var b = c.find("[data-available]");
        var a = 9999999;
        if (b.length) {
            a = b.data("available")
        }
        return crmStoreReservePopup.toFloat(a)
    },
    updateAvailableAndReserveValues: function(f) {
        var d = f.find(".reserve input");
        var a = f.find("[data-available]");
        var c = this.toFloat(d.data("reserved"));
        var e = this.toFloat(a.data("available"));
        var h = a.data("unit");
        var i = this.toFloat(d.val());
        a.text(localeAwareNumberFormat(c + e - i) + " " + h);
        var b = this.getTotalReserve();
        var g = f.parents("#reserve-products").find(".popup-subhead .in-reserve > span");
        g.text(localeAwareNumberFormat(b))
    },
    reserveOnInput: function(f) {
        var e = f.parents("tr");
        var i = f.parent();
        crmStoreReservePopup.hideWarning();
        var h = f.val();
        var c = f.data("reserved");
        var d = crmStoreReservePopup.getAvailableForTr(e);
        h = crmStoreReservePopup.toFloat(h);
        c = crmStoreReservePopup.toFloat(c);
        var a = (isNaN(c) ? 0 : c) + (isNaN(d) ? 0 : d);
        if (h < 0 || isNaN(h)) {
            crmStoreReservePopup.viewWarning(i);
            h = 0;
            f.val(localeAwareNumberFormat(h))
        } else {
            if (h > a) {
                crmStoreReservePopup.viewWarning(i);
                h = a;
                f.val(localeAwareNumberFormat(h))
            }
        }
        var g = crmStoreReservePopup.getTotalInOrder();
        var b = crmStoreReservePopup.getTotalReserve();
        if (b > g) {
            crmStoreReservePopup.viewWarning($("#" + crmStoreReservePopup.popupId + " .popup-subhead .in-reserve"));
            h = h - (b - g);
            f.val(localeAwareNumberFormat(h))
        }
        if (h > 0) {
            e.addClass(crmStoreReservePopup.trSelectedClass)
        } else {
            e.removeClass(crmStoreReservePopup.trSelectedClass)
        }
        crmStoreReservePopup.updateAvailableAndReserveValues(e)
    },
    onPopupReady: function() {
        var a = this;
        addTelephoneIcon();
        $(crmStoreReservePopup.reserveInputSelector).bind("input", function() {
            crmStoreReservePopup.reserveOnInput($(this))
        });
        $(crmStoreReservePopup.reserveInputSelector).blur(function() {
            var b = $(this).parent();
            a.hideWarning()
        });
        $("#" + crmStoreReservePopup.popupId).find("table.table-scroll").fixedHeaderTable({
            footer: false,
            cloneHeadToFoot: false,
            fixedColumn: false,
            create: function() {
                $("#" + crmStoreReservePopup.popupId).find(".stat-content").addClass("show")
            }
        });
        this.reserveOnTrClick()
    },
    updateOrderProductRow: function(d, b) {
        var a = $("#" + this.orderProductsTableId + ' input[id$="_orderProductId"][value="' + d + '"]').parents("tr");
        var c = $(b);
        $("label.mute", c).each(function() {
            var e = $(this).text();
            $(this).html(e)
        });
        $(".message-indic", c).click(commentIconClick);
        a.after(c);
        a.remove()
    },
    packRowAligment: function() {
        var b = $("#" + this.orderProductsTableId + " " + this.packRowClass);
        var a = 0;
        b.each(function() {
            var c = $(this).height();
            if (c > a) {
                a = c
            }
        });
        b.height(a)
    },
    reserveOnTrClick: function() {
        $("#" + this.popupId + " .reserve-tbl tbody tr").click(function(g) {
            var f = crmStoreReservePopup.getTotalReserve();
            var e = crmStoreReservePopup.getTotalInOrder();
            if (window.getSelection().toString()) {
                return
            }
            if ($(g.target).is(crmStoreReservePopup.excludeClickSelector)) {
                return
            }
            var c = crmStoreReservePopup.getAvailableForTr($(this));
            var b = $(this).find(".reserve input").val();
            b = crmStoreReservePopup.toFloat(b);
            var d = e - f;
            var a = $(this).find(".reserve input");
            if (d > 0) {
                if ((d + b) < c) {
                    a.val(d + b)
                } else {
                    if (c && b < c) {
                        a.val(c)
                    } else {
                        a.val(0)
                    }
                }
            } else {
                if (!b) {
                    crmStoreReservePopup.viewWarning($("#" + crmStoreReservePopup.popupId + " .popup-subhead .in-reserve"));
                    a.val(1)
                } else {
                    a.val(0)
                }
            }
            crmStoreReservePopup.reserveOnInput(a)
        })
    }
};
$(document).ready(function() {
    $("#" + crmStoreReservePopup.popupId + " .popup-footer input[name=reserve]").click(function() {
        var h = $("#" + crmStoreReservePopup.popupId).data("order-product-id");
        $(this).attr("disabled", "disabled");
        crmStoreReservePopup.sendReserve(h)
    });
    $("#" + crmStoreReservePopup.orderProductsTableId + " td.store").live("click", function() {
        if ($(".no-reserve-popup-open", this).length) {
            return
        }
        var i = $("#" + crmStoreReservePopup.popupId);
        if (i.length) {
            var h = $(this).parents("tr").find('td.title input[id$="_orderProductId"]').val();
            crmStoreReservePopup.showPopup(h)
        }
        return false
    });
    crmStoreReservePopup.onPopupReady();
    var d = $("#intaro_crmbundle_storesettingtype_use_reserve");
    c(d, $("#store_settings_main_reserve"));
    var b = $("#intaro_crmbundle_storesettingtype_allow_catalog_edit");
    var g = $("#intaro_crmbundle_storesettingtype_offers_use_product_article").parents(".control-group");
    c(b, g);
    var a = $("#intaro_crmbundle_storesettingtype_allow_inventory_edit");
    var e = $("#intaro_crmbundle_storesettingtype_inventory_refill_status").parents(".control-group");
    c(a, e);
    var f = $("#intaro_crmbundle_storesettingtype_use_stores");
    c(f, $("#store_settings_main_content"));
    c(f, $("#tab-shipment, #tab-autoreserve"));

    function c(h, i) {
        h.change(function() {
            var j = h.val();
            if (j === "0") {
                i.hide()
            } else {
                if (j === "1") {
                    i.show();
                    i.find("select").change()
                }
            }
        });
        h.change()
    }
});
var Time = (function() {
    var a = {};
    increaseMinute = function(c) {
        var f = c.text().split(":");
        if (f.length != 2) {
            return
        }
        var d = new Date();
        d.setHours(f[0], f[1]);
        d.setMinutes(d.getMinutes() + 1);
        var b = d.getHours();
        var e = d.getMinutes();
        if (b < 10) {
            b = "0" + b
        }
        if (e < 10) {
            e = "0" + e
        }
        c.text(b + ":" + e)
    };
    a.initPhoneTime = function(c) {
        var b;
        b = c.parent().attr("data-time-timer");
        if (b != undefined) {
            clearInterval(b);
            clearTimeout(b)
        }
        var d = c.text().split(":");
        if (d.length != 2) {
            return
        }
        b = setTimeout(function(f) {
            increaseMinute(f);
            var e = setInterval(increaseMinute, 60000, f);
            f.parent().attr("data-time-timer", e)
        }, (60 - (new Date()).getSeconds()) * 1000, c);
        c.parent().attr("data-time-timer", b)
    };
    a.removePhoneTime = function(c, b) {
        timerId = c.attr("data-time-timer");
        if (timerId != undefined) {
            clearInterval(timerId);
            clearTimeout(timerId)
        }
        if (b == true) {
            c.remove()
        }
    };
    return a
})();
define("ace/mode/pipelang_highlight_rules", function(e, d, f) {
    var g = e("ace/lib/oop");
    var b = e("ace/mode/text_highlight_rules").TextHighlightRules;
    var a = "[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*";
    var c = function() {
        var h = this.createKeywordMapper({
            "constant.language": "null",
            "constant.language.boolean": "true|false|TRUE|false"
        }, "identifier");
        this.$rules = {
            start: [{
                token: "comment",
                regex: "\\/\\/.*$"
            }, {
                token: "constant.numeric",
                regex: "\\d+(?:\\.\\d+)?"
            }, {
                token: "paren.lparen",
                regex: "[([{]"
            }, {
                token: "paren.rparen",
                regex: "[)\\]}]"
            }, {
                token: "string",
                regex: /("[^"\\]*(?:\\.[^"\\]*)*")|('[^'\\]*(?:\\.[^'\\]*)*')/
            }, {
                token: "keyword.operator",
                regex: /not matches(?=[\s(]|$)|matches(?=[\s(]|$)|not in(?=[\s(]|$)|b\-and(?=[\s(]|$)|b\-xor(?=[\s(]|$)|b\-or(?=[\s(]|$)|not(?=[\s(]|$)|and(?=[\s(]|$)|\=\=\=|\!\=\=|\.\.|in(?=[\s(]|$)|\<\=|\>\=|\!\=|\=\=|\=\>|or(?=[\s(]|$)|\*\*|\>|\<|\+|\-|~|\*|\/|%/
            }, {
                token: "keyword.operator",
                regex: "\\.|\\,|\\?|\\:|\\|"
            }, {
                token: h,
                regex: a
            }, {
                token: "text",
                regex: "\\s+"
            }]
        }
    };
    g.inherits(c, b);
    d.PipeLangHighlightRules = c
});
define("ace/mode/pipelang", function(d, c, e) {
    var g = d("ace/lib/oop");
    var b = d("ace/mode/text").Mode;
    var a = d("ace/mode/pipelang_highlight_rules").PipeLangHighlightRules;
    var f = function() {
        this.HighlightRules = a
    };
    g.inherits(f, b);
    (function() {}).call(f.prototype);
    c.Mode = f
});
(function(a) {
    a(function() {
        a('[data-behaviour="open-segment-letter-form"]').click(function(g) {
            g.preventDefault();
            var f = a(this);
            var c = f.attr("href") || f.data("url");
            var d = f.data("popup");
            popupOpenByUrl(c, d, function() {
                a(this).SegmentLetterForm()
            });
            var h = f.parents(".dropdown");
            if (h.length) {
                h.find("ul:visible").hide()
            }
            return false
        });
        a.fn.SegmentLetterForm = function() {
            a(this).filter(".form-horizontal").each(function() {
                b(this)
            })
        };
        var b = function(d) {
            var n = a(d);
            var k = n.find('[type="submit"]');
            var g = n.find('[name="action"]');
            var e = n.find('[data-behaviour="datepicker"]');
            var c = n.find('[data-behaviour="timepicker"]');
            var i = n.find(".date-icon");
            var o = n.find('[data-behaviour="change-email-template"]');
            var h = n.find('[data-behaviour="email-sender"]');
            var l = n.find('[data-behaviour="email-subject"]');
            var m = n.find('[data-behaviour="email-body-template"]');
            var f = n.find(".attachments-widget");
            var j = n.find(".small-text-editor");
            var p = n.find('[data-behaviour="template-loader"]');
            o.change(function() {
                var r = a(this);
                var s = r.val();
                if (s) {
                    if (!h.attr("disabled") && h.val() || l.val() || m.val()) {
                        var q = "";
                        if (!h.attr("disabled")) {
                            q = "«" + _translate("info.from_who") + "», "
                        }
                        q += _translate("info.subject_recipient_and_template");
                        if (!confirm(_translate("alert.replace_fields_by_template", {
                                fields: q
                            }))) {
                            return false
                        }
                    }
                    a.ajax({
                        url: a(this).attr("data-action").replace("___", s),
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            p.show()
                        },
                        complete: function() {
                            p.hide()
                        },
                        success: function(t) {
                            if (!h.attr("disabled") && typeof t.sender !== "undefined" && t.sender.length) {
                                h.val(t.sender)
                            }
                            l.val(t.theme);
                            m.val(t.template);
                            FormAttachmentHandler.addToList(f, t.attachments, attachments_path);
                            if (typeof(ace) !== "undefined" && j.length) {
                                var u = ace.edit(j.attr("id"));
                                u.session.setValue(t.template)
                            }
                        },
                        error: function(t) {
                            alert(CRM_ERROR_MESSAGE)
                        }
                    })
                }
            });
            k.click(function() {
                g.attr("value", a(this).data("action"));
                return true
            });
            e.datepicker({
                showOtherMonths: true,
                dateFormat: _getDateFormatString()
            });
            i.click(function() {
                if (!a("#ui-datepicker-div:visible").length) {
                    a(this).prev(e).focus()
                }
            });
            n.bind("message_form:submit", function(r, q) {
                if (q.confirmation) {
                    n.find("[data-confirmation]").html(q.confirmation);
                    n.find('[data-action="confirm"]').data("action", "send")
                }
                if (q.test_message_sent) {
                    n.find("[data-hint]").html(q.test_message_sent)
                }
            });
            c.timepicker()
        }
    })
})(jQuery);
(function(a) {
    a(function() {
        a('[data-behaviour="open-segment-sms-form"]').click(function(f) {
            f.preventDefault();
            var d = a(this);
            var c = d.attr("href") || d.data("url");
            popupOpenByUrl(c, d.data("popup"));
            var g = d.parents(".dropdown");
            if (g.length) {
                g.find("ul:visible").hide()
            }
            return false
        });
        a.fn.SegmentSmsForm = function() {
            a(this).filter(".form-horizontal").each(function() {
                b(this)
            })
        };
        var b = function(f) {
            var c = a(f);
            var h = c.find('[type="submit"]');
            var d = c.find('[data-behaviour="template-loader"]');
            var g = c.find('[name="action"]');
            var i = c.find('[data-behaviour="sms-body-template"]');
            var e = c.find(".small-text-editor");
            a('[type="submit"]').click(function() {
                g.attr("value", a(this).data("action"));
                return true
            });
            a('[data-behaviour="change-sms-template"]').change(function() {
                var j = a(this);
                var k = j.val();
                if (k) {
                    if (i.val()) {
                        if (!confirm(_translate("alert.replace_fields_by_template", {
                                fields: ""
                            }))) {
                            return false
                        }
                    }
                    a.ajax({
                        url: a(this).attr("data-action").replace("___", k),
                        type: "GET",
                        dataType: "json",
                        beforeSend: function() {
                            d.show()
                        },
                        complete: function() {
                            d.hide()
                        },
                        success: function(l) {
                            i.val(l.template);
                            if (typeof(ace) != "undefined" && e.length) {
                                var m = ace.edit(e.attr("id"));
                                m.session.setValue(l.template)
                            }
                        },
                        error: function(l) {
                            alert(CRM_ERROR_MESSAGE)
                        }
                    })
                }
            })
        }
    })
})(jQuery);
jQuery(document).ready(function() {
    enableAutocomplete();
    var b = $("#order-cost-edit-popup");
    var a = $("#tbl-body-costs");
    $("#new-cost").live("click", function(c) {
        c.preventDefault();
        b.intaroPopup({
            url: $(this).attr("href"),
            onSuccess: function() {
                $(".datepicker", b).datepicker()
            }
        })
    });
    $("input.datepicker.cost_date-from").live("change", function() {
        var d = $(this);
        var c = $("input.datepicker.cost_date-to");
        if (!c.val()) {
            c.val(d.val())
        }
    });
    $("#cost-edit-popup-submit").live("click", function(d) {
        d.preventDefault();
        var e = $(this);
        var c = $("#order-cost-edit-form");
        $.ajax({
            url: c.attr("action"),
            type: "post",
            data: c.serialize(),
            beforeSend: function() {
                e.addClass("disabled");
                e.attr("disabled", "disabled");
                $(".ajax-progress", e.parent()).css("display", "inline-block")
            },
            success: function(f) {
                if (f.status === "error") {
                    c.replaceWith(f.view);
                    $(".datepicker", b).datepicker()
                } else {
                    a.html(f.tableBody);
                    b.intaroPopup("close")
                }
            },
            complete: function() {
                e.removeClass("disabled");
                e.removeAttr("disabled");
                $(".ajax-progress", e.parent()).css("display", "none")
            },
            error: function() {
                alert(_translate("alert.add_order_cost_error"))
            }
        })
    });
    $(".order-cost-delete-btn").live("click", function() {
        if (confirm(_translate("confirm.delete_cost"))) {
            var e = $(this);
            var d = e.data("url");
            var c = $("span", e);
            c.removeClass("tr-delete");
            c.addClass("red-loader");
            $.ajax({
                url: d,
                type: "post",
                success: function(f) {
                    if (f.status === "success") {
                        a.html(f.tableBody)
                    }
                }
            })
        }
    });
    $(".cost-list.list .cost-item a").live("click", function(c) {
        c.preventDefault()
    });
    $(".cost-list.list td:not(.no-click)").live("click", function() {
        var c = $(this).parent().find("td:first-child a").attr("href");
        if (typeof c === "undefined") {
            return
        }
        popupOpenByUrl(c, "order-cost-edit-popup")
    });
    $(".choice-group").live("change", function() {
        var h = $(".choice-group option:selected");
        var f = $("#source-block");
        var c = $("#applies-to-orders-block");
        var e = $("#applies-to-users-block");
        var d = $("#crm_cost_item_type_type");
        if (h.hasClass("for-attraction")) {
            f.show();
            c.hide();
            e.hide();
            c.find("input").removeAttr("checked");
            e.find("input").removeAttr("checked");
            d.val(1);
            var g = $('<div class="gray-bg fs13"/>');
            g.html(d.find(":selected").html());
            d.parent().find(".chosen-container").hide();
            d.parent().append(g);
            f.find("select.chosen").chosen().each(function() {
                var i = $(this).data("chosen");
                i.search_field_scale()
            })
        } else {
            f.hide();
            c.show();
            e.show();
            d.parent().find(".gray-bg").remove();
            d.parent().find(".chosen-container").show()
        }
    });
    $(".choice-cost-item").live("change", function() {
        var d = $(".choice-cost-item option:selected");
        var c = $("#cost-user-block");
        if (d.data("user") === 1) {
            c.show()
        } else {
            c.hide()
        }
    });
    $(".choice-cost-item").trigger("change");
    $(".form-reload").live("change", function() {
        var f = $(this).parents(".form-fields");
        var c = $(this).parents("form");
        var e = $(this).parents(".input-group");
        var d = $("span.form-reload-loader", e);
        d.removeClass("hide");
        $.ajax({
            url: f.data("url"),
            type: "post",
            data: c.serialize(),
            success: function(g) {
                if (g.formFields) {
                    f.html(g.formFields);
                    $(".datepicker").datepicker();
                    enableAutocomplete();
                    $(".chosen").chosen()
                }
            },
            complete: function() {
                d.addClass("hide")
            }
        })
    });
    $("#filter_costGroups").chosen().change(function() {
        var d = $("#filter_costItems");
        var c = $("#filter_costGroups").val();
        d.find("optgroup").remove();
        if (c == null) {
            c = Object.keys(costGroupMap)
        }
        c.forEach(function(g, f, e) {
            var h = $("<optgroup/>").attr("label", costGroupMap[g]["name"]);
            costGroupMap[g]["items"].forEach(function(j, i, k) {
                h.append($("<option/>").attr("value", j.id).html(j.name))
            });
            d.append(h)
        });
        d.trigger("chosen:updated")
    })
});