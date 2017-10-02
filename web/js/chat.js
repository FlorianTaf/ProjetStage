$(document).ready(function () {
    $('#chat').append('COUCOU');
    var chat = $('#messagesChat').DataTable({
        "order": [["0", "desc"]],
        processing: true,
        serverSide: true,
        dom: 'C<"clear">lfrtip',
        "scrollX": true,
        ajax: {
            "url": "{{ path('ft_chat_messages') }}",
            "type": "POST",
            "columns" : [
                {"data" : "content"},
                {"data" : "datePosted"},
                {"data" : "sender"},
            ]
        }
    });
});