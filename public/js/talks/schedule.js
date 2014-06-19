$(document).ready(function () {
    $('table[id|="talks"]').each(function () {
        var id = this.id;
        var date = id.substring(6);
        
        $.ajax(
            {
                url: baseUrl + 'schedule',
                data: {
                    date: date
                },
                dataType: 'json',
                type: 'GET',
                success: function (items) {
                    if (items.length > 0) {
                        renderItems(id, items);
                        
                        return ;
                    }
                    
                    hideTable(id);
                }
            }
        );
    });
});

function hideTable(tableId)
{
    $('#' + tableId).parent().parent().fadeOut('fast');
}

function renderItems(tableId, scheduledItems)
{
    var rooms = extractRooms(scheduledItems);
    var items = {
        data: scheduledItems,
        rooms: rooms,
        i: null
    };
    
    $('#' + tableId).append(createHeader(rooms));
    $('#' + tableId).append(createBody(items));
    
    $('.speakers').popover();
    $('.tooltips').tooltip();
    $('a[id|="talk"]').unbind('click');
    
    $('a[id|="talk"]').click(function () {
        var id = this.id.split('-')[1];

        $.ajax(
            {
                url: baseUrl + 'talk/' + id,
                type: 'GET',
                dataType: 'json',
                success: function (talk) {
                    openTalk(talk);
                }
            }
        );
        
        return false;
    });
    
    $('#talkWindow').on('hidden.bs.modal', function () {
        $('#talkWindow .modal-header h3 span').text('');
        $('#talkWindow .modal-header h3 small').text('');
        $('#talkWindow #speakers').html('<label></label>');
        $('#evaluation #community-evaluation').html('').css('display', 'none');
        $('#talkWindow #talkTags').html('<label>Tags</label>');
    });
}

function openTalk(talk)
{
    $('#talkWindow .modal-header h3 span').text(talk.title);
    $('#talkWindow .modal-header h3 small').text(talk.type.toLowerCase());
    $('#talkWindow #speakers label').text(talk.speakers.length == 1 ? 'Palestrante' : 'Palestrantes');
    $('#talkWindow #shortDescription').html(talk.shortDescription.replace(/\n/g, '<br />'));
    
    for (var i = 0; i < talk.speakers.length; ++i) {
        $('#talkWindow #speakers').append(
            '<div style="' + (i > 0 ? 'margin-top: 10px' : '') + '">'
                + '<img src="' + talk.speakers[i].avatar + '" alt="'
                    + talk.speakers[i].name + '" class="img-thumbnail" width="55"> '
                + talk.speakers[i].name
            + '</div>'
        );
    }
    
    if (talk.tags.length > 0) {
        $('#talkWindow #talkTags').append('<br />');
    }
    
    for (var i = 0; i < talk.tags.length; ++i) {
        $('#talkWindow #talkTags').append(
            '<span class="' + getLabel(i) + '" style="text-transform: capitalize;">' + talk.tags[i] + '</span> '
        );
    }
    
    showTalkSummary(talk);
    
    $('#talkWindow').modal('show');
}

function getLabel(index)
{
    var labels = [
        'label label-primary',
        'label label-success',
        'label label-warning',
        'label label-danger',
        'label label-default',
        'label label-info'
    ];

    return labels[index % labels.length];
}

function showTalkSummary(talk)
{
    $.ajax(
        {
            url: baseUrl + 'talk/' + talk.id + '/summary',
            type: 'GET',
            dataType: 'json',
            success: function (summary) {
                $('#talkWindow #community-evaluation').html(
                    '<div class="well">'
                        + '<label>Votos da comunidade</label>'
                        + '<div class="row">'
                            + '<div class="col-md-6">'
                                + '<span class="label label-info">'
                                    + '<span class="glyphicon glyphicon-thumbs-up"></span>'
                                + '</span> ' + summary.likes + '</div>'
                            + '<div class="col-md-6">'
                                + '<span class="label label-warning">'
                                + '<span class="glyphicon glyphicon-thumbs-down"></span>'
                            + '</span> ' + summary.dislikes + '</div>'
                        + '</div>'
                    + '</div>'
                );
                $('#talkWindow #community-evaluation').fadeIn('slow');
            }
        }
    );
}

function createHeader(rooms)
{
    var header = '<thead><tr><th class="col-md-1"></th>';
    var room;
    
    for (var i = 0; i < rooms.length; ++i) {
    	room = rooms[i];
        
        header += '<th>'
        	+ room.name
        	+ (room.details ? ' <small>(' + room.details + ')</small>' : '')
        	+ '</th>';
    }
    
    header += '</tr></thead>';
    
    return header;
}

function createBody(items)
{
    var body = '<tbody>';
    
    for (items.i = 0; items.i < items.data.length; ++items.i) {
        var item = items.data[items.i];
        
        body += '<tr>';
        body += '<th>' + getItemTime(item.startTime) + '</th>'

        if (!item.room) {
            body += '<td colspan="' + items.rooms.length + '" style="text-align: center;">' + item.label + '</td>'
        } else {
            body += getItemsColumns(item, items);
        }
        
        body += '</tr>';
    }
    
    body += '</tbody>';
    
    return body;
}

function getItemTime(timeString)
{
    var time = (new Date(timeString)).toLocaleTimeString();
    
    time = time.replace(/\u200E/g, '');
    time = time.replace(/^([^\d]*\d{1,2}:\d{1,2}):\d{1,2}([^\d]*)$/, '$1$2');
    
    return time;
}

function getItemsColumns(current, items)
{
    var columns = '';
    
    for (var i = 0; i < items.rooms.length; ++i) {
    	if (current.startTime != items.data[items.i].startTime) {
    		break;
    	}
    	
    	if (items.data[items.i].room.id != items.rooms[i].id) {
    		columns += '<td />';
    		continue;
    	}
    	
    	columns += renderColumn(items.data[items.i], items.data[items.i + 1], items.rooms.length - i);
    	++items.i;
    	
    	if (!items.data[items.i]) {
    		break;
    	}
    }
    
    --items.i;
    
    return columns;
}

function renderColumn(item, next, colspan)
{
    var column = '<td';
    
    if (!next || item.startTime != next.startTime) {
        column += ' colspan="' + colspan + '"';
    }
    
    column += '>';
    
    if (item.talk) {
        column += '<a href="#" id="talk-' + item.talk.id + '">' + item.label + '</a>';

        if (item.talk.cost > 0) {
            column += '<span class="label label-info tooltips" style="margin-left: 5px" title="Para assistir deve ser pago a parte o valor de R$ ' + item.talk.cost + '" data-placement="auto"><span class="glyphicon glyphicon-shopping-cart"></span></span>';
        }
        
        for (var i = 0; i < item.talk.speakers.length; ++i) {
            column += '<br /><span class="speakers" style="line-height: 35px; vertical-align: middle; cursor: pointer" title="' + item.talk.speakers[i].name + '" data-content="' + item.talk.speakers[i].bio + '" data-placement="auto" data-trigger="hover">';
            column += '<img src="' + item.talk.speakers[i].avatar + '" class="img-thumbnail" style="width: 35px; padding: 2px; margin-right: 4px " />';
            column += item.talk.speakers[i].name + '</span>';
        }
        
    } else {
        column += item.label;
    }
    
    column += '</td>';
    
    return column;
}

function extractRooms(items)
{
    var rooms = [];
    var added = [];
    
    for (var i = 0; i < items.length; ++i) {
    	if (!items[i].room || added[items[i].room.id]) {
    		continue;
    	}
    	
    	rooms.push(items[i].room);
    	added[items[i].room.id] = true;
    }
    
    rooms.sort(function (one, other) {return one.id - other.id; });
    
    return rooms;
}