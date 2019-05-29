months = [
    'jan', 'fev', 'mar', 'abr', 'mai', 'jun', 'jul', 'ago', 'set', 'out', 'nov', 'dez'
];

$(document).ready(function() {
    $('#loader').delay(500).fadeOut();
});

$('#counter').ready(function() {
    var countdown = setInterval(function() {
        var days = $('#counter-days').text();
        var hours = $('#counter-hours').text();
        var min = $('#counter-min').text();
        var sec = $('#counter-seconds').text();

        sec--;
        if (sec < 0) {
            if (min > 0) {
                sec = 59;
                min--;
            } else {
                if (hours > 0) {
                    hours--;
                    sec = 59;
                    min = 59;
                } else {
                    if (days > 0) {
                        days--;
                        sec = 59;
                        min = 59;
                        hours = 23;
                    } else {
                        sec = 0;
                        min = 0;
                        hours = 0;
                        days = 0;
                        clearInterval(countdown);
                    }
                }
            }
        }

        $('#counter-days').text(days);
        $('#counter-hours').text(hours);
        $('#counter-min').text(min);
        $('#counter-seconds').text(sec);
    }, 1000);
});

$("#creditos #partners-logos img").hover(function() {
    var partnerName = $(this).attr("data-partner-name");
    $("#partner-name").toggleClass("active").text(partnerName);
});

$(".fa-bars").click(function() {
    if ($("#nav-wrap").hasClass("active")) {
        $("#nav-wrap").fadeOut(100).removeClass("active");
        if ($(window).scrollTop() == 0 && !$('header').hasClass('no-animation')) {
            $("header").removeClass("scroll");
            if($('.logo').hasClass('upfolder')) {
                $("header .logo").attr('src', '../media/logo.png');
            } else {
                $("header .logo").attr('src', 'media/logo.png');
            }
        }
    } else {
        $("#nav-wrap").fadeIn(100).addClass("active");
        $("header").not('.no-animation').addClass("scroll");
        if($('.logo').hasClass('upfolder') && !$('header').hasClass('no-animation')) {
            $("header .logo").attr('src', '../media/logo-minimal.png');
        } else if(!$('header').hasClass('no-animation')) {
            $("header .logo").attr('src', 'media/logo-minimal.png');
        }
    }
});

$('#nav-wrap ul li a').click(function() {
    $('.fa-bars').trigger('click');
});

$(window).scroll(function() {
    var height = $(window).scrollTop();
    if (height > 10 && !$('header').hasClass('no-animation')) {
        $("header").addClass("scroll");
        if($('.logo').hasClass('upfolder')) {
            $("header .logo").attr('src', '../media/logo-minimal.png');
        } else {
            $("header .logo").attr('src', 'media/logo-minimal.png');
        }
    } else if(!$('header').hasClass('no-animation')) {
        if($('.logo').hasClass('upfolder')) {
            $("header .logo").attr('src', '../media/logo.png');
        } else {
            $("header .logo").attr('src', 'media/logo.png');
        }
        $("header").removeClass("scroll");
    }

    $("#nav-wrap").fadeOut(100).removeClass("active");
    if ($(window).scrollTop() == 0 && !$('header').hasClass('no-animation')) {
        $("header").removeClass("scroll");
        if($('.logo').hasClass('upfolder')) {
            $("header .logo").attr('src', '../media/logo.png');
        } else {
            $("header .logo").attr('src', 'media/logo.png');
        }
    }
});

$(function() {
    $('a[href*="#"]:not([href="#"])').click(function() {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').delay(20).animate({ scrollTop: target.offset().top - 110 }, 500);
                return false;
            }
        }
    });

    event_id = $('param#event').attr('data');

    $('.cpfmask').mask('000.000.000-00', { reverse: false });
    $('.phonemask').mask('(00) 00000-0000');
    $('.moneymask').mask("#.##0,00", { reverse: true });

    $('.masked-form').submit(function() {
        $('.masked-input').each(function(idx, item) {
            var value = $(item).cleanVal();
            $(item).unmask().val(value);
        });
        return true;
    });

    $('#nav-wrap .fa-times').click(function() { $(".fa-bars").trigger("click"); });
    $('#register-submit').click(submitRegister);
    $('#set-traveling').click(setEventConfig);
    $('#add-assistlink-button').click(setAssistLink);
    $('#add-author-button').click(addAuthor);
    $('#profile-form #btn-update').click(updateUser);
    $('#submissions-table .fa-times').click(disapproveSubmission);
    $('#submissions-table .fa-check').click(approveSubmission);
    $('#partners-table .fa-trash').click(deletePartner);
    $('#cancel-partner-button').click(closeEditPartner);
    $('#partners-table .fa-pencil-alt').click(openEditPartner);
    $('#editTicket #update-ticket-button').click(updateTicket);
    $('#editTicket #cancel-ticket-button').click(closeEditTicket);
    $('#tickets-table .fa-pencil-alt').click(openEditTicket);
    $('#editCoupon #cancel-coupon-button').click(closeEditCoupon);
    $('#update-coupon-button').click(updateCoupon);
    $('#coupon-table .fa-pencil-alt').click(openEditCoupon);
    $('#cancel-speaker-button').click(closeEditSpeaker);
    $('#speakers-table .fa-pencil-alt').click(openEditSpeaker);
    $('#speakers-table .fa-trash').click(deleteSpeaker);
    $('#editDayAsGroup #day-edit-button').click(editDay);
    $('#editAct #act-edit-button').click(editAct);
    $('#editAct #act-cancel-button').click(closeEditAct);
    $('.editDayGroup').click(openEditDayAsGroup);
    $('#editDayAsGroup #day-cancel-button').click(closeEditDayAsGroup);
    $('#acts .act .blue').click(openEditAct);
    $('#acts .act .red').click(deleteAct);
    $('#news-table .fa-pencil-alt.green').click(openEditNews);
    $('#editNews #edit-new-cancel-button').click(closeEditNews);
    $('#editPostGraduation #cancel-postgraduation-button').click(function() { $("#editPostGraduation").fadeOut(); });
    $('#postgraduations-table .fa-pencil-alt').click(openPostGraduationEditForm);
    $('#postgraduations-table .fa-trash').click(deletePostGraduation);
    $('#delete-event').click(deleteEvent);
    $('#type-button').click(createType);
    $('#types-table .fa-trash').click(deleteType);
    $('#event-state').change(loadCities);
    $('#submit-event').click(createEvent);
    $('button.event.button').click(openEvent);
    $('button.event-submission.button').click(openEventSubmission);
    $('button.event-registration.button').click(openEventSubscription);
    $('.btn-page').click(openPage);
    $('.news').click(openNotice);
    $('#notice-panel .fa-times').click(closeNotice);
    $('#left-button').click(prevSlide);
    $('#right-button').click(nextSlide);
    $('#participant-table .fa-trash').click(removeUser);
    //$('#participant-table .fa-search').click(openUserInfo);
    $('#participant-table .fa-pencil-alt').click(openEditUser);
    $('#participant-cancel-button').click(cancelEditUser);
    $('#participant-update-button').click(editUser);
    $('#news-table .fa-trash').click(deleteNews);
    $(".slide").eq(0).addClass("active");
    $("#arrows i.fa-angle-right").click(nextSpeaker);
    $("#arrows i.fa-angle-left").click(prevSpeaker);
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

    $('#slides .slide').each(function(idx, item) {
        $(item).css('background-image', 'url(' + $(item).attr('data-image') + ')');
    });

    $('#home.event').css('background-image', 'url(' + $('#home.event').attr('data-image') + ')');
    $('#contagem').css('background-image', 'url(' + $('#contagem').attr('data-image') + ')');
    $(".day-block").first().trigger('click');

    speakerCounting = 0;
});

function loadTravelPage(link) {
    const div = $('div.assistLink');
    div.load('capturePage.php?link=' + link);
}

function submitRegister() {
    $(this).parent().parent().find('form').submit();
}

function setEventConfig() {
    if (confirm('Deseja realmente fazer essa operaÃƒÂ§ÃƒÂ£o?')) {
        var travel = $(this).val();

        $.ajax({
                method: "POST",
                url: "../php/ajax/eventConfig.php",
                data: {
                    update: true,
                    travel: travel,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    window.location.reload();
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

function setAssistLink() {
    if (confirm('Deseja realmente atualizar a URL de hospedagem?')) {
        var link = $('#assistlink-form #assistlink-link').val();

        $.ajax({
                method: "POST",
                url: "../php/ajax/assistLink.php",
                data: {
                    update: true,
                    link: link,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    dialogBox.find(".message").text("InformaÃƒÂ§ÃƒÂ£o atualizada com sucesso!");
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

function removeUser() {
    if (confirm('Deseja realmente excluir a participaÃƒÂ§ÃƒÂ£o desse usuÃƒÂ¡rio?')) {
        var cpf = $(this).parent().attr('data-cpf');
        var row = $(this).parent().parent();
        $.ajax({
                method: "POST",
                url: "../php/ajax/subscribes.php",
                data: {
                    delete: true,
                    cpf: cpf,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    row.remove();
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

function removeAuthor() {
    var nameVal = $(this).parent().attr('id');
    $('div#' + nameVal).remove();
    $('input#' + nameVal).remove();
}

function addAuthor() {
    var name = $('#submission-authors-name');
    var email = $('#submission-authors-email');

    const nameVal = name.val();
    const emailVal = email.val();

    if (nameVal != '' && emailVal != '') {
        name.val('');
        email.val('');

        var authorInfo = $('<div class="author-info" id="' + nameVal + '"><span>' + nameVal + ' &#60' + emailVal + '&#62</span><i class="fas fa-times"></i></div>');
        var authorInfoInput = $('<input type="hidden" value="' + nameVal + '%' + emailVal + '" id="' + nameVal + '" name="author[]">');
        authorInfo.find('.fa-times').click(removeAuthor);

        $('#co-authors-group').append(authorInfo);
        $('#submission-form').append(authorInfoInput);
    }

}

function updateUser() {
    var form = $(this).parent().parent();
    var cpf = form.find("#cpf").val();
    var name = form.find("#name").val();
    var email = form.find("#email").val();
    var phone = form.find("#phone").cleanVal();
    var password = form.find("#password").val();
    var estado = form.find("#estado").val();
    var course = form.find("#course").val();
    var formation = form.find("#formation").val();

    $.ajax({
            method: "POST",
            url: "../painel/php/ajax/user.php",
            data: {
                update: true,
                cpf: cpf,
                name: name,
                email: email,
                phone: phone,
                password: password,
                estado: estado,
                course: course,
                formation: formation
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                window.location.reload();
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

function updateSubmission(id, status, button) {
    $.ajax({
            method: "POST",
            url: "../php/ajax/submission.php",
            data: {
                update: true,
                status: status,
                id: id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                var className = "";
                var text = "";
                switch (status) {
                    case 0:
                        className = "pending";
                        text = "Em avaliaÃƒÂ§ÃƒÂ£o";
                        break;
                    case 1:
                        className = "ok";
                        text = "Aprovado";
                        break;
                    case -1:
                        className = "error";
                        text = "Reprovado";
                        break;
                }
                button.parent().parent().find("#submission-status-td").html('<i class="fas fa-circle ' + className + '"></i> ' + text);
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

function approveSubmission() {
    if (confirm("Tem certeza que deseja aprovar esse trabalho?")) {
        var id = $(this).parent().attr('data-id');
        var button = $(this);

        updateSubmission(id, 1, button);
    }
}

function disapproveSubmission() {
    if (confirm("Tem certeza que deseja reprovar esse trabalho?")) {
        var id = $(this).parent().attr('data-id');
        var button = $(this);

        updateSubmission(id, -1, button);
    }
}

function updateTicket() {
    var id = $("#editTicket #update-ticket-button").attr('data-id');

    var name = $("#editTicket #ticket-name").val();
    var initialDate = $("#editTicket #ticket-initial-date").val();
    var finalDate = $("#editTicket #ticket-final-date").val();
    var description = $("#editTicket #ticket-description").val();
    var price = $("#editTicket #ticket-value").val();

    $.ajax({
            method: "POST",
            url: "../php/ajax/ticket.php",
            data: {
                update: true,
                id: id,
                name: name,
                initialDate: initialDate,
                finalDate: finalDate,
                description: description,
                price: price
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                window.location.reload();
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

    closeEditTicket();
}

function closeEditTicket() {
    $("#editTicket").fadeOut();
}

function openEditTicket() {
    var id = $(this).parent().attr('data-id');
    var name = $(this).parent().attr('data-name');
    var initialDate = $(this).parent().attr('data-initial-date');
    var finalDate = $(this).parent().attr('data-final-date');
    var description = $(this).parent().attr('data-description');
    var price = $(this).parent().attr('data-price');

    $('#editTicket #ticket-name').val(name);
    $('#editTicket #ticket-initial-date').val(initialDate);
    $('#editTicket #ticket-final-date').val(finalDate);
    $('#editTicket #ticket-description').val(description);
    $('#editTicket #ticket-value').val(price);

    $("#editTicket #update-ticket-button").attr('data-id', id);
    $('#editTicket').fadeIn();
}

function updateCoupon() {
    var discount = $('#editCoupon #coupon-discount').val();
    var code = $('#editCoupon #coupon-name').val();
    var oldCode = $('#editCoupon #update-coupon-button').attr('data-code');
    $.ajax({
            method: "POST",
            url: "../php/ajax/coupon.php",
            data: {
                update: true,
                oldCode: oldCode,
                code: code,
                discount: discount,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                window.location.reload();
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
    closeEditCoupon();
}

function openEditCoupon() {
    var discount = $(this).parent().attr('data-discount');
    var code = $(this).parent().attr('data-code');

    $('#editCoupon #coupon-name').val(code);
    $('#editCoupon #coupon-discount').val(discount);
    $('#editCoupon #update-coupon-button').attr('data-code', code);
    $('#editCoupon').fadeIn();
}

function closeEditCoupon() {
    $('#editCoupon').fadeOut();
}

function openEditSpeaker() {
    var name = $(this).parent().attr('data-name');
    var description = $(this).parent().attr('data-description');
    $("#editSpeaker #speaker-old-name").val(name);
    $("#editSpeaker #speaker-name").val(name);
    $("#editSpeaker #speaker-description").val(description);
    $("#editSpeaker").fadeIn();
}

function closeEditPartner() {
    $("#editPartner").fadeOut();
}

function openEditPartner() {
    var name = $(this).parent().attr('data-name');
    var link = $(this).parent().attr('data-link');
    $("#editPartner #partner-name-input").val(name);
    $("#editPartner #partner-old-name").val(name);
    $("#editPartner #partner-link").val(link);
    $("#editPartner").fadeIn();
}

function closeEditSpeaker() {
    $("#editSpeaker").fadeOut();
}

function deleteSpeaker() {
    if (confirm("Tem certeza que deseja excluir esse palestrante?")) {
        var name = $(this).parent().attr('data-name');
        var button = $(this);
        $.ajax({
                method: "POST",
                url: "../php/ajax/speaker.php",
                data: {
                    delete: true,
                    name: name,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
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

function deletePartner() {
    if (confirm("Tem certeza que deseja excluir esse parceiro?")) {
        var name = $(this).parent().attr('data-name');
        var button = $(this);
        $.ajax({
                method: "POST",
                url: "../php/ajax/partner.php",
                data: {
                    delete: true,
                    name: name,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
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

function openEditDayAsGroup() {
    var date = $(this).parent().attr('data-time');
    $("#editDayAsGroup form").attr('data-time', date);
    $("#editDayAsGroup #day-date").val(date);
    $("#editDayAsGroup").fadeIn();
}

function closeEditNews() {
    $("#editNews").fadeOut();
}

function openEditNews() {
    var info = $(this).parent();
    var title = info.attr('data-title');
    var content = info.attr('data-content');
    var id = info.attr('data-id');

    $('#news-id').val(id);
    $('#edit-new-title').val(title);
    $('#edit-new-content').text(content);
    $("#editNews").fadeIn();
}

function openEditAct() {
    var time = $(this).parent().attr('data-full-time');
    var title = $(this).parent().attr('data-title');

    var actName = $('#editAct').find('#act-name');
    var actDate = $('#editAct').find('#act-date');
    var actTime = $('#editAct').find('#act-time');
    var actFinalTime = $('#editAct').find('#act-time-final');
    var actButton = $('#editAct').find('#act-edit-button');

    $.ajax({
            method: "POST",
            url: "../php/ajax/schedule.php",
            data: {
                search: true,
                title: title,
                time: time,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                actName.val(response.title);
                actDate.val(response.date);
                actTime.val(response.time);
                actFinalTime.val(response.finalTime);
                actButton.attr('data-time', time).attr('data-title', title).attr('data-time-final', response.finalTime);

                $("#editAct").fadeIn();
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

function closeEditDayAsGroup() {
    $("#editDayAsGroup").fadeOut();
}

function closeEditAct() {
    $("#editAct").fadeOut();
}

function deleteAct() {
    if (confirm("Tem certeza que deseja excluir essa aÃƒÂ§ÃƒÂ£o?")) {
        var time = $(this).parent().attr('data-full-time');
        var title = $(this).parent().attr('data-title');

        var button = $(this);
        $.ajax({
                method: "POST",
                url: "../php/ajax/schedule.php",
                data: {
                    delete: true,
                    title: title,
                    time: time,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    button.parent().remove();
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

function editDay() {
    var date = $(this).parent().parent().find('#day-date').val();
    var oldDate = $(this).parent().parent().parent().attr('data-time');

    $.ajax({
            method: "POST",
            url: "../php/ajax/schedule.php",
            data: {
                updateAsGroup: true,
                dateUpdate: date,
                oldDate: oldDate,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                window.location.reload();
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

function editAct() {
    var actButton = $('#editAct #act-edit-button');
    var time = actButton.attr('data-time');
    var title = actButton.attr('data-title');

    var dateUpdate = $("#editAct #act-date").val();
    var finalTimeUpdate = $("#editAct #act-time-final").val();
    var timeUpdate = $("#editAct #act-time").val();
    var titleUpdate = $("#editAct #act-name").val();

    $.ajax({
            method: "POST",
            url: "../php/ajax/schedule.php",
            data: {
                update: true,
                title: title,
                time: time,
                dateUpdate: dateUpdate,
                titleUpdate: titleUpdate,
                timeUpdate: timeUpdate,
                finalTimeUpdate: finalTimeUpdate,
                event: event_id
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                window.location.reload();
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

function deleteEvent() {
    if (confirm("Tem certeza que deseja excluir esse evento?")) {
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
                if (response.success) {
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
        alert("OperaÃƒÂ§ÃƒÂ£o cancelada!");
    }
}

function selectDay() {
    var dayBlock = $(this);
    selected_time = dayBlock.attr('data-time');
    $('.day-block').removeClass('active');
    dayBlock.addClass('active');

    $("#acts .act").removeClass('active');
    $("#acts .act").each(function(idx, item) {
        if ($(item).attr('data-time-ref') == dayBlock.attr('data-time')) {
            $(item).addClass('active');
        }
    });
}

function deletePostGraduation() {
    if (confirm("Tem certeza que deseja excluir essa pÃƒÂ³s-graduaÃƒÂ§ÃƒÂ£o?")) {
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
                if (response.success) {
                    dialogBox.find(".message").text("PÃƒÂ³s-graduaÃƒÂ§ÃƒÂ£o excluÃƒÂ­da com sucesso!");
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
            if (response.success) {
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
    if (confirm("Tem certeza que deseja excluir essa ÃƒÂ¡rea de estudo?")) {
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
                if (response.success) {
                    dialogBox.find(".message").text("ÃƒÂrea de estudo excluÃƒÂ­da com sucesso!");
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
            if (response.success) {
                dialogBox.find(".message").text("ÃƒÂrea de estudo adicionada com sucesso!");
                dialogBox.fadeIn();
                setTimeout(function() {
                    dialogBox.fadeOut();
                }, 5000);

                var tr = $("<tr></tr>");
                var name = $("<td>" + response.type.name + "</td>");
                var trash = $('<td data-id="' + response.type.id + '"><i class="fas fa-trash pointer red"></i></td>');

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
            if (response.success) {
                var noticeFrame = $("#notice-frame");
                noticeFrame.find('h3').text(response.notice[0].title);
                noticeFrame.find('p').text(response.notice[0].message);
                noticeFrame.find('#notice-file').attr('href', '..' + response.notice[0].file);

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
    const eventInput = $('<input type="hidden" name="event" value="' + event_id + '">');
    const priceInput = $('<input type="hidden" name="price" value="' + price + '">');
    const couponInput = $('<input type="hidden" name="coupon" value="' + coupon + '">');
    const ticketInput = $('<input type="hidden" name="ticket" value="' + ticket + '">');
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
            if (response.success) {
                setTimeout(function() {
                    infoText.text("Validado com sucesso!");

                    $('#discount').html('<small class="uppercase">desconto</small> ' + response.coupon.discount + '%');

                    $('.card.active').attr('data-coupon', response.coupon.code);

                    var newPrice = parseFloat($('.card.active').attr('data-price'));
                    newPrice = newPrice - (newPrice * (response.coupon.discount / 100));

                    $("#total").html('<small class="uppercase">total</small> R$ ' + newPrice);
                }, 1000);
            } else {
                setTimeout(function() {
                    infoText.text("Cupom invÃƒÂ¡lido").addClass('error').removeClass('green');

                    $('#discount').html('<small class="uppercase">desconto</small> 0%');

                    var newPrice = parseFloat($('.card.active').attr('data-price'));

                    $("#total").html('<small class="uppercase">total</small> R$ ' + newPrice);

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
    if (ticket_id != button.attr('data-ticket')) {
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

        cart.find('.fa-times').click(function() {
            ticket_id = null;
            $('.card a.button-ticket').html('<i class="fas fa-shopping-cart"></i> Comprar').removeClass('green');
            $('.card a.button-ticket').off('mouseenter mouseleave');
            $('.card').removeClass('active');
            $('#cart').fadeOut();
            $('#coupon-setter').fadeOut();
        });
        cart.find('#name').html(button.parent().find('.card-title').text());
        cart.find('#subtotal').html('<small class="uppercase">subtotal</small> ' + price);
        cart.find('#discount').html('<small class="uppercase">desconto</small> 0%');
        cart.find('#total').html('<small class="uppercase">total</small> ' + price);

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
    if (activeSlide.next().is(".slide")) {
        activeSlide.removeClass("active").next().addClass("active");
    } else {
        activeSlide.removeClass("active");
        $(".slide").eq(0).addClass("active");
    }
}

function prevSlide() {
    var activeSlide = $(".slide.active");
    if (activeSlide.prev().is(".slide")) {
        activeSlide.removeClass("active").prev().addClass("active");
    } else {
        activeSlide.removeClass("active");
        $(".slide").last().addClass("active");
    }
}

function nextSpeaker() {
    var activeSpeaker = $(".speaker.active");
    if (activeSpeaker.last().next().is(".speaker")) {
        activeSpeaker.removeClass("active").next().addClass("active");
    } else {
        activeSpeaker.removeClass("active");
        for (let index = 0; index < 8; index++) {
            $(".speaker:not(.active)").first().addClass('active');
        }
    }

    activeSpeaker = $('.speaker.mobile');
    if (activeSpeaker.next().is(".speaker")) {
        activeSpeaker.removeClass("mobile").next().addClass("mobile");
    } else {
        activeSpeaker.removeClass("mobile");
        $(".speaker").first().addClass('mobile');
    }
}

function prevSpeaker() {
    var activeSpeaker = $(".speaker.active");
    if (activeSpeaker.first().prev().is(".speaker")) {
        activeSpeaker.removeClass("active").prev().addClass("active");
    } else {
        activeSpeaker.removeClass("active");
        for (let index = 0; index < 8; index++) {
            $(".speaker:not(.active)").last().addClass('active');
        }
    }

    activeSpeaker = $('.speaker.mobile');
    if (activeSpeaker.prev().is(".speaker")) {
        activeSpeaker.removeClass("mobile").prev().addClass("mobile");
    } else {
        activeSpeaker.removeClass("mobile");
        $(".speaker:not(.mobile)").last().addClass('mobile');
    }
}

function updateDeadlineSetter() {
    if ($(this).prop('checked')) {
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
    window.open("event/?event=" + id, "_self");
}

function openEventSubscription() {
    var id = $(this).attr("id");
    window.open("registration/?event=" + id, "_self");
}

function openEventSubmission() {
    var id = $(this).attr("id");
    window.open("submit/?event=" + id, "_self");
}

function openPage() {
    var btn = $(this);
    var page = $(btn.attr("page"));
    $(".page").removeClass("active");
    page.addClass("active");
}

function settingEventConfig() {
    if (confirm("VocÃƒÂª tem certeza que deseja atualizar as informaÃƒÂ§ÃƒÂµes do evento?")) {
        var name = $("#event-name").val();
        var date = $("#event-date").val();
        var endDate = $("#event-end-date").val();
        var description = $("#event-description").val();
        var allow = $(this).parent().find("#allow-event-submissions").prop('checked') ? "1" : "0";
        var subscription = $("#event-subscription").val();

        var deadline = "";
        if (allow == "1") {
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
                    endDate: endDate,
                    description: description,
                    deadline: deadline,
                    subscription: subscription,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    dialogBox.find(".message").text("AtualizaÃƒÂ§ÃƒÂµes feitas com sucesso!");
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
            if (response.success) {
                //
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

function deleteNews() {
    if (confirm("Tem certeza que deseja apagar essa notÃƒÂ­cia?")) {
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
                if (response.success) {
                    dialogBox.find(".message").text("NotÃƒÂ­cia deletada com sucesso.");
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

function createAct() {
    if (selected_time) {
        var form = $(this).parent().parent();
        var actTitle = form.find('#act-name').val();
        var actTime = form.find('#act-time').val();
        var actFinalTime = form.find('#act-time-final').val();
        var time = selected_time + " " + actTime;
        var finalTime = selected_time + " " + actFinalTime;

        $.ajax({
                method: "POST",
                url: "../../painel/php/ajax/schedule.php",
                data: {
                    add: true,
                    title: actTitle,
                    time: time,
                    finalTime: finalTime,
                    event: event_id
                }
            })
            .done(function(response) {
                response = $.parseJSON(response);
                if (response.success) {
                    dialogBox.find(".message").text("AÃƒÂ§ÃƒÂ£o criada com sucesso.");
                    dialogBox.fadeIn();

                    var act = $('<div class="act active" data-time-ref="' + selected_time + '" data-full-time="' + time + '" data-title="' + actTitle + '"></div>');
                    var title = $('<h5 class="bold uppercase">' + actTitle + ' <span class="regular">' + actTime + ' ~ ' + actFinalTime + '</span></h5>');
                    var editButton = $('<div class="uppercase regular pointer button blue"><i class="fas fa-pencil-alt pointer"></i> Editar</div>');
                    var deleteButton = $('<div class="uppercase regular pointer button red"><i class="fas fa-trash pointer"></i> Excluir</div>');

                    editButton.click(openEditAct);
                    deleteButton.click(deleteAct);

                    act.append(title).append(editButton).append(deleteButton);
                    $("#acts").append(act);
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
        dialogBox.find(".message").text("Crie ou selecione um dia para criar uma aÃƒÂ§ÃƒÂ£o.");
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

    var time = year + "-" + month + "-" + day;

    var block = $('<div class="day-block" data-time="' + time + '"></div>');
    var textDay = $('<h1 class="black">' + day + '</h1>');
    var textMonth = $('<p class="regular">' + months[month - 1] + '</p>');

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
            if (response.success) {
                dialogBox.find(".message").text("InscriÃƒÂ§ÃƒÂ£o realizada. Aguarde enquanto redirecionamos vocÃƒÂª.");
                dialogBox.fadeIn();
                setTimeout(function() {
                    window.open("../event/?event=" + event_id, "_self")
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

function editUser() {
    var name = $('#participant-name').val();
    var email = $('#participant-email').val();
    var cpf = $('#participant-cpf').val();
    var phone = $('#participant-phone').val();
    var password = $('#participant-password').val();
    var estado = $('#participant-estado').val();
    var course = $('#participant-course').val();
    var formation = $('#participant-formation').val();
    var oldCpf = $('#participant-old-cpf').val();

    $.ajax({
        method: "POST",
        url: "../../painel/php/ajax/user.php",
        data: {
            update: true,
            cpf: cpf,
            name: name,
            email: email,
            phone: phone,
            password: password,
            estado: estado,
            course: course,
            formation: formation,
            oldCpf: oldCpf
        }
    })
    .done(function(response) {
        response = $.parseJSON(response);
        if(response.success) {
            alert('Participante atualizado com sucesso');
        }
        $('#editParticipant').fadeOut();
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    });
}

function openEditUser() {
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

        $('#participant-name').val(response.user.name);
        $('#participant-email').val(response.user.email);
        $('#participant-cpf').val(response.user.cpf);
        $('#participant-password').val(response.user.password);
        $('#participant-phone').val(response.user.phone);
        $('#participant-estado').val(response.user.estado);
        $('#participant-course').val(response.user.course);
        $('#participant-formation').val(response.user.formation);
        $('#participant-old-cpf').val(response.user.cpf);
    })
    .fail(function(jqXHR, textStatus, msg) {
        alert(msg);
    }); 

    $('#editParticipant').fadeIn();
} 

function cancelEditUser() { 
    $('#editParticipant').fadeOut();
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
            if (response.success) {
                var stateName;

                states.forEach(function(element, index) {
                    if (element.id == response.user.estado_id) {
                        stateName = element.name;
                    }
                });

                $('#user-name').text(response.user.name);
                $('#user-email').text(response.user.email);
                $('#user-cpf').text(response.user.cpf);
                $('#user-phone').text(response.user.phone);
                $('#user-estado').text(stateName);
                $('#user-course').text(response.user.course);
                $('#user-formation').text(response.user.formation);

                var statusText = [
                    'NÃƒÂ£o processada',
                    'Boleto nÃƒÂ£o gerado',
                    'Aguardando pagamento',
                    'Em anÃƒÂ¡lise',
                    'Paga',
                    'DisponÃƒÂ­vel',
                    'Em disputa',
                    'Devolvida',
                    'Cancelada',
                    'Debitado',
                    'RetenÃƒÂ§ÃƒÂ£o temporÃƒÂ¡ria'
                ];

                var statusColor = '';

                $('#user-status').children().remove();
                response.transactions.forEach(function(element, index) {
                    var statusIdx = Number.parseInt(element.status) + 1;

                    if (statusIdx == 0 || statusIdx == 7 || statusIdx == 8 || statusIdx == 9) {
                        statusColor = 'error';
                    } else if (statusIdx == 4 || statusIdx == 5) {
                        statusColor = 'ok';
                    } else {
                        statusColor = 'pending';
                    }

                    if (element.coupon_code == null) var row = $('<span class="uppercase bold"><i class="fas fa-circle ' + statusColor + '"></i> ' + statusText[statusIdx] + ' | ' + element.transactionDate + ' | Valor: R$ ' + element.value + ' | Cupom: Nenhum</span>');
                    else var row = $('<span class="uppercase bold"><i class="fas fa-circle ' + statusColor + '"></i> ' + statusText[statusIdx] + ' | ' + element.transactionDate + ' | Valor: R$ ' + element.value + ' | Cupom: ' + element.coupon_code + '</span>');
                    var br = $('<br>');
                    $('#user-status').append(row).append(br);
                });

                $('#participant-info').fadeIn();
                $('#participant-info .fa-times').click(function() {
                    $('#participant-info').fadeOut();
                });
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
            if (response.success) {
                var tbody = $("#coupon-table").find("tbody");
                var tr = $("<tr></tr>");
                var dataName = $("<td>" + name + "</td>");
                var dataValue = $("<td>" + value + "%</td>");
                var dataSearch = $('<td data-code="' + name + '"><i class="fas fa-trash pointer red"></i></td>');

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
    if (confirm("Tem certeza que deseja excluir esse cupom?")) {
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
                if (response.success) {
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
    var initialDate = form.find("#ticket-initial-date").val();
    var finalDate = form.find("#ticket-final-date").val();
    var value = form.find("#ticket-value").val();

    $.ajax({
            method: "POST",
            url: "../../painel/php/ajax/ticket.php",
            data: {
                ticket: true,
                event: event_id,
                name: name,
                description: description,
                initialDate: initialDate,
                finalDate: finalDate,
                value: value
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) {
                var tbody = $("#tickets-table").find("tbody");
                var tr = $("<tr></tr>");
                var dataName = $("<td>" + name + "</td>");
                var dataValue = $("<td> R$ " + value + "</td>");
                var dataInitialDate = $("<td>" + initialDate + "</td>");
                var dataFinalDate = $("<td>" + finalDate + "</td>");
                var dataDescription = $("<td>" + description + "</td>");
                var dataSearch = $('<td data-id="' + response.ticket.id + '" data-name="' + response.ticket.name + '" data-price="' + response.ticket.price + '" data-description="' + response.ticket.description + '" data-initial-date="' + response.ticket.initialDate + '" data-final-date="' + response.ticket.finalDate + '"><i class="fas fa-trash red pointer"></i> <i class="fas fa-pencil-alt green pointer"></i></td>');

                dataSearch.children().eq(0).click(deleteTicket);
                dataSearch.children().eq(1).click(openEditTicket);

                tr.append(dataName);
                tr.append(dataValue);
                tr.append(dataInitialDate);
                tr.append(dataFinalDate);
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
    if (confirm("Tem certeza que deseja excluir essa entrada?")) {
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
                if (response.success) {
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
            if (response.success) updateCities(response.cities);
            else alert(response.message);
        })
        .fail(function(jqXHR, textStatus, msg) {
            alert(msg);
        });
}

function updateCities(cities) {
    $('#event-city').removeAttr('disabled').children().remove();
    cities.forEach(element => {
        var option = $('<option>' + element.nome + '</option>').val(element.id);
        $('#event-city').append(option);
    });
}

function createEvent() {
    var form = $("#createEventForm");
    var name = form.find("#event-name").val();
    var endDate = form.find("#event-end-date").val();
    var date = form.find("#event-date").val();
    var city = form.find("#event-city").val();

    $.ajax({
            method: "POST",
            url: "php/ajax/event.php",
            data: {
                create: true,
                name: name,
                date: date,
                endDate: endDate,
                city: city
            }
        })
        .done(function(response) {
            response = $.parseJSON(response);
            if (response.success) refreshEvents();
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
            if (response.success) updateEvents(response.events);
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
        var button = $('<button type="button" class="event button green" id="' + event.id + '">' + event.name + '</button>');
        button.click(openEvent);
        div.append(button);
    });
}

$(function() {
    $('#schedule-slides .day-selector ul li').click(function() {
        var day = $(this).attr('data-day');
        $('#schedule-slides .day').each(function() {
            if ($(this).attr('data-day') == day) {
                $('#schedule-slides .day.active').removeClass('active');
                $(this).addClass('active');
            }
        });
        $('.day-selector ul li.active').removeClass('active');
        $(this).addClass('active');
    });

    $("#send-mail").click(function() {
        $("#mailing-popup").css('display', 'flex').css('opacity', 0).animate({
            opacity: 1
        }, 250);
    });

    $("#confirm-mailing-popup").click(function() {
        var users = [];

        $("#mailing-popup table tbody tr").each(function() {
            if ($(this).find('input').prop('checked')) {
                users.push({
                    'email': $(this).attr('data-email'),
                    'name': $(this).attr('data-name')
                });
            }
        });

        var subject = $("#mail-subject").val();
        var message = $(".trumbowyg-editor").html();

        $("#mailing-popup").fadeOut();
        $("#progress-mailing").fadeIn().css('display', 'flex');

        var sendSuccess = 0;
        var count = users.length;
        $("#progress-mailing span.pending").text(count + ' envios pendentes');

        for (let index = 0; index < users.length; index++) {
            const user = users[index];
            $.ajax({
                    method: "POST",
                    url: "../php/ajax/send-mailing.php",
                    data: {
                        send: true,
                        subject: subject,
                        message: message,
                        user: user['email'],
                        userName: user['name']
                    }
                })
                .done(function(response) {
                    count -= 1;
                    sendSuccess += 1;
                    $("#progress-mailing span.pending").text(count + ' envios pendentes');
                    $("#progress-mailing span.success").text(sendSuccess + ' envios concluí­dos');
                })
                .fail(function(jqXHR, textStatus, msg) {
                    alert(msg);
                });
        }
    });

    $("#close-mailing-popup").click(function() {
        $("#mailing-popup").fadeOut();
    });

    $("#progress-mailing button").click(function() {
        $('#progress-mailing').fadeOut();
    });
}); 