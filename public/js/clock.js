function initClock() {
    var canvas = document.getElementById("clock");
    var ctx = canvas.getContext("2d");
    var radius = canvas.height / 2;
    ctx.translate(radius, radius);
    radius = radius * 0.90
    drawClock(ctx, radius);
    setInterval(function () {
        drawClock(ctx, radius);
    }, 1000);
}

function drawClock(ctx, radius) {
    ctx.clearRect(-radius, -radius, 2 * radius, 2 * radius);
    drawTime(ctx, radius);
}

function drawTime(ctx, radius) {
    var now = new Date();
    var hour = now.getHours();
    var minute = now.getMinutes();
    var second = now.getSeconds();
    //hour
    hour = hour % 12;
    hour = (hour * Math.PI / 6) +
            (minute * Math.PI / (6 * 60)) +
            (second * Math.PI / (360 * 60));
    drawHand(ctx, hour, radius * 0.5, radius * 0.07, '#6d3702', 0);
    //minute
    minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
    drawHand(ctx, minute, radius * 0.65, radius * 0.07, '#6d3702', 0);
    // second
    second = (second * Math.PI / 30);
    drawHand(ctx, second, radius * 0.8, radius * 0.03, '#ec3f00', radius * 0.06);
    ctx.arc(0, 0, radius * 0.08, 0, 2 * Math.PI);
    ctx.fill();
}

function drawHand(ctx, pos, length, width, color, arc) {
    ctx.beginPath();
    ctx.strokeStyle = color;
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0, 0);
    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.beginPath();
    if (arc != 0) {
        ctx.fillStyle = color;
        ctx.arc(0, -(length * 0.7), arc, 0, 2 * Math.PI);
    }
    ctx.fill();
    ctx.rotate(-pos);
}

function drawHandOld(ctx, pos, length, width) {
    ctx.beginPath();
    ctx.lineWidth = width;
    ctx.lineCap = "round";
    ctx.moveTo(0, 0);

    ctx.rotate(pos);
    ctx.lineTo(0, -length);
    ctx.stroke();
    ctx.rotate(-pos);
}
