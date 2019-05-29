months = [
    'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'
];

$("#parceiros .featured").hover(function() {
    var partnerName = $(this).children().eq(0).attr("data-partner-name");
    $("#partner-name").toggleClass("active").text(partnerName);
});

$(".fa-bars").click(function() {
    if ($("#nav-wrap").hasClass("active")) {
        $("#nav-wrap").fadeOut(100).removeClass("active");
        if ($(window).scrollTop() == 0) $("header:not(.no-animation)").removeClass("scroll");
    } else {
        $("#nav-wrap").fadeIn(100).addClass("active");
        $("header:not(.no-animation)").addClass("scroll");
    }
});

$(window).scroll(function() {
    var height = $(window).scrollTop();
    if (height > 10) {
        $("header:not(.no-animation)").addClass("scroll");
    } else {
        $("header:not(.no-animation)").removeClass("scroll");
    }

    updateDots(height);

    $("#nav-wrap").fadeOut(100).removeClass("active");
    if ($(window).scrollTop() == 0) $("header:not(.no-animation)").removeClass("scroll");
});

function updateDots(height) {
    var section;
    $("section").each(function() {
        var offset = $(this).offset();
        if (height >= (offset.top - 180)) {
            section = $(this);
        }
    });

    var index;

    index = section.attr("dot-index");

    if (index == 0) {
        $("#floating-dots").addClass("white");
    } else {
        $("#floating-dots").removeClass("white");
    }

    $(".dot").removeClass("active");
    $(".dot:eq(" + index + ")").addClass("active");

}

$(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').delay(20).animate({ scrollTop: target.offset().top }, 500);
                return false;
            }
        }
    });

    event_id = $('param#event').attr('data');

    $('#editPostGraduation #cancel-postgraduation-button').click(function() { $("#editPostGraduation").fadeOut(); });
    $('#postgraduations-table .fa-pencil-alt').click(openPostGraduationEditForm);
    $('#postgraduations-table .fa-trash').click(deletePostGraduation);
    $('#delete-event').click(deleteEvent);
    $('#type-button').click(createType);
    $('#types-table .fa-trash').click(deleteType);
    $('#event-state').change(loadCities);
    $('#submit-event').click(createEvent);
    $('button.event.button').click(openEvent);
    $('button.event-registration.button').click(openEventSubscription);
    $('.btn-page').click(openPage);
    $('.news').click(openNotice);
    $('#notice-panel .fa-times').click(closeNotice);
    $('#left-button').click(prevSlide);
    $('#right-button').click(nextSlide);
    $('#participant-table .fa-search').click(openUserInfo);
    $('#news-table .fa-trash').click(deleteNews);
    $(".slide").eq(0).addClass("active");
    $("#speakers i.fa-angle-right").click(nextSpeaker);
    $("#speakers i.fa-angle-left").click(prevSpeaker);
    $("#speakers .speaker").first().addClass("active");
    $("#allow-event-submissions").change(updateDeadlineSetter);
    $("#event-config-button").click(settingEventConfig);
    $(".button-ticket").click(selectTicket);
    $("#coupon-setter #add-coupon").click(addCoupon);
    $("#buy-button").click(createPayment);
    $("#coupon-button").click(createCoupon);
    $("#coupon-table .fa-trash").click(deleteCoupon);
    $("#ticket-button").click(createTicket);
    $("#tickets-table .fa-trash").click(deleteTicket);
    $("#day-add-button").click(createDay);
    $("#act-add-button").click(createAct);
    $(".day-block").click(selectDay);
    $("#add-day").click(function() {
        $("#day-insert").fadeIn();
    });
    $("#schedule-page i.fa-times").click(function() {
        $(this).parent().parent().parent().fadeOut();
    });
    dialogBox = $("#dialogBox");
    dialogBox.children().find('.fa-times').click(function() {
        dialogBox.fadeOut();
    });

    ticket_id = null;
    
    $('#slides .slide').each(function(idx, item){
        $(item).css('background-image', 'url('+$(item).attr('data-image')+ ')');
    });

    $('#home.event').css('background-image', 'url('+$('#home.event').attr('data-image')+')');
    $(".day-block").first().trigger('click');
});

function deleteEvent() {
    if(prompt("Para excluir esse evento, digite 'confirmar'").toLowerCase() == "confirmar") {
        $.ajax({
            method: "POST",
            url: "../php/ajax/event.php",
            data: { 
                delete: true,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                window.open('../', '_self');
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    } else {
        alert("Operação cancelada!");
    }
}

function selectDay() {
    var dayBlock = $(this);
    selected_time = dayBlock.attr('data-time');
    $('.day-block').removeClass('active');
    dayBlock.addClass('active');
    
    $("#acts .act").removeClass('active');
    $("#acts .act").each(function(idx, item) {
        if($(item).attr('data-time-ref') == dayBlock.attr('data-time')) {
            $(item).addClass('active');
        }
    });
}

function deletePostGraduation() {
    if(confirm("Tem certeza que deseja excluir essa pós-graduação?")) {
        var button = $(this);
        var id = button.parent().attr('data-id');

        $.ajax({
            method: "POST",
            url: "php/ajax/postgraduation.php",
            data: { 
                delete: true,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                dialogBox.find(".message").text("Pós-graduação excluída com sucesso!");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);

                button.parent().parent().remove();
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function openPostGraduationEditForm() {
    var id = $(this).parent().attr('data-id');

        $.ajax({
            method: "POST",
            url: "php/ajax/postgraduation.php",
            data: { 
                search: true,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                var id = response.postGraduation.id;
                var name = response.postGraduation.name;
                var description = response.postGraduation.description;
                var link = response.postGraduation.link;
                var image = response.postGraduation.image;

                var form = $("#editPostGraduation form");
                form.find("#postgraduation-id").val(id);
                form.find("#postgraduation-name").val(name);
                form.find("#postgraduation-description").val(description);
                form.find("#postgraduation-link").val(link);
                form.find("#postgraduation-image").val(image);
                $("#editPostGraduation").fadeIn();
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
        
}

function deleteType() {
    if(confirm("Tem certeza que deseja excluir essa área de estudo?")) {
        var button = $(this);
        var id = $(this).parent().attr('data-id');

        $.ajax({
            method: "POST",
            url: "../php/ajax/type.php",
            data: { 
                delete: true,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                dialogBox.find(".message").text("Área de estudo excluída com sucesso!");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);

                button.parent().parent().remove();
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function createType() {
    var name = $('#type-name').val();

    $.ajax({
        method: "POST",
        url: "../php/ajax/type.php",
        data: { 
            add: true,
            name: name,
            event: event_id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            dialogBox.find(".message").text("Área de estudo adicionada com sucesso!");
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);

            var tr = $("<tr></tr>");
            var name = $("<td>"+response.type.name+"</td>");
            var trash = $('<td data-id="'+response.type.id+'"><i class="fas fa-trash pointer red"></i></td>');

            tr.append(name).append(trash);
            $("#types-table tbody").append(tr);
        } else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function closeNotice() {
    $(this).parent().parent().fadeOut();
}

function openNotice() {
    var id = $(this).attr('data-id');

    $.ajax({
        method: "POST",
        url: "../painel/php/ajax/news.php",
        data: { 
            get: true,
            id: id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            var noticeFrame = $("#notice-frame");
            noticeFrame.find('h3').text(response.notice[0].title);   
            noticeFrame.find('p').text(response.notice[0].message);   
            noticeFrame.find('#notice-file').attr('href', '..'+response.notice[0].file);  
            
            noticeFrame.parent().fadeIn(100).delay(100).css('display', 'flex');
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function createPayment() {
    const price = $('.card.active').attr('data-price');
    const coupon = $('.card.active').attr('data-coupon');
    const ticket = ticket_id;

    const form = $('<form action="../../painel/php/operations/payment.php" method="post"></form>');
    const eventInput = $('<input type="hidden" name="event" value="'+event_id+'">');
    const priceInput = $('<input type="hidden" name="price" value="'+price+'">');
    const couponInput = $('<input type="hidden" name="coupon" value="'+coupon+'">');
    const ticketInput = $('<input type="hidden" name="ticket" value="'+ticket+'">');
    const buttonInput = $('<input type="hidden" name="buy-button" value="">');

    form.append(eventInput).append(priceInput).append(couponInput).append(ticketInput).append(buttonInput);
    $(document.body).append(form);
    form.submit();
}

function addCoupon() {
    var code = $(this).parent().find('#coupon-name').val();
    const infoText = $('#coupon-setter small'); 
    
    infoText.text('Validando...').addClass('green').removeClass('error');
    
    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/coupon.php",
        data: { 
            get: true,
            code: code,
            event: event_id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            setTimeout(function() {
                infoText.text("Validado com sucesso!");

                $('#discount').html('<small class="uppercase">desconto</small> '+response.coupon.discount+ '%');

                $('.card.active').attr('data-coupon', response.coupon.code);

                var newPrice = parseFloat($('.card.active').attr('data-price'));
                newPrice = newPrice-(newPrice*(response.coupon.discount/100));

                $("#total").html('<small class="uppercase">total</small> R$ '+newPrice);
            }, 1000);
        }
        else {
            setTimeout(function() {
                infoText.text("Cupom inválido").addClass('error').removeClass('green');

                $('#discount').html('<small class="uppercase">desconto</small> 0%');

                var newPrice = parseFloat($('.card.active').attr('data-price'));

                $("#total").html('<small class="uppercase">total</small> R$ '+newPrice);

                setTimeout(function() {
                    infoText.text('');
                }, 4000);
            }, 1000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function selectTicket() {
    var button = $(this);
    event_id = button.attr('data-event');
    if(ticket_id != button.attr('data-ticket')) {
        ticket_id = button.attr('data-ticket');
        
        $('.card a.button-ticket').html('<i class="fas fa-shopping-cart"></i> Comprar').removeClass('green');
        $('.card a.button-ticket').off('mouseenter mouseleave');
        $('.card').removeClass('active');
        
        button.parent().parent().addClass('active');
        button.html('<i class="fas fa-check"></i> Selecionado').addClass('green clicked').mouseenter(function() {
            button.html('<i class="fas fa-times"></i> Remover');
        }).mouseleave(function() {
            button.html('<i class="fas fa-check"></i> Selecionado');
        });
        const cart = $('#cart');
        const couponSetter = $('#coupon-setter');

        const price = button.parent().find('.price').text();

        cart.find('#name').html(button.parent().find('.card-title').text());
        cart.find('#subtotal').html('<small class="uppercase">subtotal</small> '+price);
        cart.find('#discount').html('<small class="uppercase">desconto</small> 0%');
        cart.find('#total').html('<small class="uppercase">total</small> '+price);

        cart.fadeIn();
        couponSetter.fadeIn();
    } else {
        ticket_id = null;
        $('.card a.button-ticket').html('<i class="fas fa-shopping-cart"></i> Comprar').removeClass('green');
        $('.card a.button-ticket').off('mouseenter mouseleave');
        $('.card').removeClass('active');
        $('#cart').fadeOut();
        $('#coupon-setter').fadeOut();
    }
}

function nextSlide() {
    var activeSlide = $(".slide.active");
    if(activeSlide.next().is(".slide")) {
        activeSlide.removeClass("active").next().addClass("active");
    } else {
        activeSlide.removeClass("active");
        $(".slide").eq(0).addClass("active");
    }
}

function prevSlide() {
    var activeSlide = $(".slide.active");
    if(activeSlide.prev().is(".slide")) {
        activeSlide.removeClass("active").prev().addClass("active");
    } else {
        activeSlide.removeClass("active");
        $(".slide").last().addClass("active");
    }
}

function nextSpeaker() {
    var activeSpeaker = $(".speaker.active");
    if(activeSpeaker.next().is(".speaker")) {
        activeSpeaker.removeClass("active").next().addClass("active");
    } else {
        activeSpeaker.removeClass("active");
        $(".speaker").eq(0).addClass("active");
    }
}

function prevSpeaker() {
    var activeSpeaker = $(".speaker.active");
    if(activeSpeaker.prev().is(".speaker")) {
        activeSpeaker.removeClass("active").prev().addClass("active");
    } else {
        activeSpeaker.removeClass("active");
        $(".speaker").last().addClass("active");
    }
}

function updateDeadlineSetter() {
    if($(this).prop('checked')) {
        $("#event-deadline").prop('disabled', false);
        $("#type-name").prop('disabled', false);
        $("#type-button").prop('disabled', false);
    } else {
        $("#event-deadline").prop('disabled', true);
        $("#type-name").prop('disabled', true);
        $("#type-button").prop('disabled', true);
    }
}

function openEvent() {
    var id = $(this).attr("id");
    window.open("event/?event="+id, "_self");
}

function openEventSubscription() {
    var id = $(this).attr("id");
    window.open("registration/?event="+id, "_self");
}

function openPage() {
    var btn = $(this);
    var page = $(btn.attr("page"));
    $(".page").removeClass("active");
    page.addClass("active");
}

function settingEventConfig() {
    if(confirm("Você tem certeza que deseja atualizar as informações do evento?")) {
        var name = $("#event-name").val();
        var date = $("#event-date").val();
        var description = $("#event-description").val();
        var allow = $(this).parent().find("#allow-event-submissions").prop('checked') ? "1" : "0";
        var subscription = $("#event-subscription").val();

        var deadline = "";
        if(allow == "1") {
            deadline = $("#event-deadline").val();
        }

        $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/event.php",
            data: { 
                update: true,
                allowSubmissions: allow,
                name: name,
                date: date,
                description: description,
                deadline: deadline,
                subscription: subscription,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                dialogBox.find(".message").text("Atualizações feitas com sucesso!");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
            else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function getNews() {
    var id = $(this).attr('data-id');

    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/news.php",
        data: { 
            get: true,
            id: id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            //
        }
        else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function deleteNews() {
    if(confirm("Tem certeza que deseja apagar essa notícia?")) {
        var id = $(this).parent().attr('data-id');
        var button = $(this);

        $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/news.php",
            data: {
                delete: true,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                dialogBox.find(".message").text("Notícia deletada com sucesso.");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);

                button.parent().parent().remove();
            }
            else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function createAct() {
    if(selected_time) {
        var form = $(this).parent().parent();
        var actTitle = form.find('#act-name').val();
        var actTime = form.find('#act-time').val();
        var time = selected_time + " " + actTime;

        $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/schedule.php",
            data: { 
                add: true,
                title: actTitle,
                time: time,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                dialogBox.find(".message").text("Ação criada com sucesso.");
                dialogBox.fadeIn();

                var act = $('<div class="act active" data-time-ref="'+selected_time+'" data-full-time="'+time+'"></div>');
                var title = $('<h5 class="bold uppercase">'+actTitle+' <span class="regular">'+actTime+'</span></h5>');
                var editButton = $('<div class="uppercase regular pointer button blue"><i class="fas fa-pencil-alt pointer"></i> Editar</div>');
                var deleteButton = $('<div class="uppercase regular pointer button red"><i class="fas fa-trash pointer"></i> Excluir</div>');

                act.append(title).append(editButton).append(deleteButton);
                $("#acts").append(act);
            }
            else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    } else {
        dialogBox.find(".message").text("Crie ou selecione um dia para criar uma ação.");
        dialogBox.fadeIn();
        setTimeout(function() {
            dialogBox.fadeOut();
        }, 5000);
    }
}

function createDay() {
    var form = $(this).parent().parent();
    var day = form.find("#day-input-title").val();
    day = day.split("-");
    
    var year = day[0];
    var month = day[1];
    var day = day[2];

    var time = year+"-"+month+"-"+day;

    var block = $('<div class="day-block" data-time="'+time+'"></div>');
    var textDay = $('<h1 class="black">'+day+'</h1>');
    var textMonth = $('<p class="regular">'+months[month-1]+'</p>');

    block.append(textDay).append(textMonth);
    $("#add-day").last().before(block);

    $("#day-insert").fadeOut();

    block.click(selectDay);
}

function confirmRegistration() {
    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/registration.php",
        data: { 
            registration: true,
            event: event_id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            dialogBox.find(".message").text("Inscrição realizada. Aguarde enquanto redirecionamos você.");
            dialogBox.fadeIn();
            setTimeout(function() {
                window.open("../event/?event="+event_id, "_self") 
            }, 5000);
        }
        else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function openUserInfo() {
    var cpf = $(this).parent().attr('data-cpf');

    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/user.php",
        data: { 
            search: true,
            cpf: cpf,
            event: event_id
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            $('#user-name').text(response.user.name);
            $('#user-email').text(response.user.email);
            $('#user-cpf').text(response.user.cpf);
            $('#user-birth').text(response.user.birth);

            var statusText = [
                'Não processada',
                'Em espera',
                'Aguardando pagamento',
                'Em análise',
                'Paga',
                'Disponível',
                'Em disputa',
                'Devolvida',
                'Cancelada',
                'Debitado',
                'Retenção temporária'
            ];

            var statusColor = '';

            $('#user-status').children().remove();

            response.transactions.forEach(function(element, index) {
                var statusIdx = Number.parseInt(element.status) + 1;
                
                if(statusIdx == 0 || statusIdx == 7 || statusIdx == 8 || statusIdx == 9) {
                    statusColor = 'error';
                } else if (statusIdx == 4 || statusIdx == 5) {
                    statusColor = 'ok';
                } else {
                    statusColor = 'pending';
                }
                
                var row = $('<span class="uppercase bold"><i class="fas fa-circle '+statusColor+'"></i> '+statusText[statusIdx]+' | '+element.transactionDate+'</span>');
                var br = $('<br>');
                $('#user-status').append(row).append(br);
            });

            $('#participant-info').fadeIn();
            $('#participant-info .fa-times').click(function() {
                $('#participant-info').fadeOut();
            });
        }
        else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function createCoupon() {
    var form = $("#coupon-form");
    var name = form.find("#coupon-name").val();
    var value = form.find("#coupon-discount").val();

    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/coupon.php",
        data: { 
            coupon: true,
            event: event_id,
            name: name,
            discount: value
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            var tbody = $("#coupon-table").find("tbody");
            var tr = $("<tr></tr>");
            var dataName = $("<td>"+name+"</td>");
            var dataValue = $("<td>"+value+"%</td>");
            var dataSearch = $('<td data-code="'+name+'"><i class="fas fa-trash pointer red"></i></td>');

            tr.append(dataName);
            tr.append(dataValue);
            tr.append(dataSearch);

            tbody.append(tr);

            dialogBox.find(".message").text("Cupom adicionado com sucesso.");
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        } else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function deleteCoupon() {
    if(confirm("Tem certeza que deseja excluir esse cupom?")) {
        var name = $(this).parent().attr('data-code');
        var tr = $(this).parent().parent();
        
        $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/coupon.php",
            data: { 
                delete: true,
                name: name,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                tr.remove();

                dialogBox.find(".message").text("Cupom removido com sucesso.");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function createTicket() {
    var form = $("#tickets-form");
    var name = form.find("#ticket-name").val();
    var description = form.find("#ticket-description").val();
    var value = form.find("#ticket-value").val();

    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/ticket.php",
        data: { 
            ticket: true,
            event: event_id,
            name: name,
            description: description,
            value: value
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            var tbody = $("#tickets-table").find("tbody");
            var tr = $("<tr></tr>");
            var dataName = $("<td>"+name+"</td>");
            var dataValue = $("<td> R$ "+value+"</td>");
            var dataDescription = $("<td>"+description+"</td>");
            var dataSearch = $('<td data-id="'+response.ticket.id+'"><i class="fas fa-trash red pointer"></i></td>');

            tr.append(dataName);
            tr.append(dataValue);
            tr.append(dataDescription);
            tr.append(dataSearch);

            tbody.append(tr);

            dialogBox.find(".message").text("Entrada adicionada com sucesso.");
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        } else {
            dialogBox.find(".message").text(response.message);
            dialogBox.fadeIn();
            setTimeout(function() {
                dialogBox.fadeOut();
            }, 5000);
        }
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function deleteTicket() {
    if(confirm("Tem certeza que deseja excluir essa entrada?")) {
        var id = $(this).parent().attr('data-id');
        var tr = $(this).parent().parent();
        
        $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/ticket.php",
            data: { 
                delete: true,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if(response.success) {
                tr.remove();

                dialogBox.find(".message").text("Entrada removida com sucesso.");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            } else {
                dialogBox.find(".message").text(response.message);
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);
            }
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
    }
}

function loadCities() {
    $.ajax({
        method: "POST",
        url: "php/ajax/city.php",
        data: { 
            byState: true,
            state: $('#event-state').val()
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) updateCities(response.cities);
        else alert(response.message);
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function updateCities(cities) {
    $('#event-city').removeAttr('disabled').children().remove();
    cities.forEach(element => {
        var option = $('<option>'+element.nome+'</option>').val(element.id);
        $('#event-city').append(option);
    });
}

function createEvent() {
    var form = $("#createEventForm");
    var name = form.find("#event-name").val();
    var date = form.find("#event-date").val();
    var city = form.find("#event-city").val();

    $.ajax({
        method: "POST",
        url: "php/ajax/event.php",
        data: {
            create: true,
            name: name,
            date: date,
            city: city
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) refreshEvents(); 
        else alert(response.message);
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg)
    });

    form.each(function() { this.reset(); });
    form.find("#event-city").children().remove();
    
    $("#close-event-modal").trigger('click');
}

function refreshEvents() {
    $.ajax({
        method: "POST",
        url: "php/ajax/event.php",
        data: {
            searchAll: true
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) updateEvents(response.events);
        else alert(response.message);
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function updateEvents(events) {
    var div = $('#events');
    div.children().remove();
    events.forEach(event => {
        var button = $('<button type="button" class="event button green" id="'+event.id+'">'+event.name+'</button>');
        button.click(openEvent);
        div.append(button);
    });
}