$(document).ready(function () {
    if (!!window.EventSource) {
        const logOutput = $('#processes');
        logOutput.text('');
        const source = new EventSource('/showProcesses.php');

        source.addEventListener('beginOfStream', function () {
            logOutput.append("Stream started.\n");
        }, false);

        source.addEventListener('open', function () {
            console.log('Event Source opened.');
        }, false);

        source.addEventListener('clear', function (e) {
            logOutput.html('');
        }, false);

        source.addEventListener('message', function (e) {
            console.dir(e);
            logOutput.append(e.data + "\n");
        }, false);

        source.addEventListener('endOfStream', function () {
            source.close();
            logOutput.append("Stream ended.\n");
        }, false);

        source.addEventListener('error', function (e) {
            if (e.readyState === EventSource.CLOSED) {
                console.log('Event Source closed.');
                source.close();
            }
        }, false);
    } else {
        alert('No event source available. Please use another, modern browser!');
    }

    $('#left').find('button').click(function (e) {
        e.preventDefault();
        let connection = $('#connection').val();
        console.dir(connection);
        let callMethod = $(this).attr('id');
        if (!!window.EventSource) {
            const output = $('#output');
            output.text('');
            let es = new EventSource('/createPDFs.php?callMethod=' + callMethod + '&connection=' + connection);

            es.addEventListener('beginOfStream', function () {
                output.append("Stream started.\n");
            }, false);

            es.addEventListener('open', function () {
                console.log('Event Source opened.');
            }, false);

            es.addEventListener('message', function (e) {
                output.append(e.data + "\n");
                output.scrollTop(9999999);
            }, false);

            es.addEventListener('endOfStream', function () {
                output.append("Stream ended.\n");
                output.scrollTop(9999999);
                es.close();
            }, false);

            es.addEventListener('error', function (e) {
                if (e.readyState === EventSource.CLOSED) {
                    console.log('Event Source closed.');
                    es.close();
                }
            }, false);
        } else {
            alert('No event source available. Please use another, modern browser!');
        }
    });
});
