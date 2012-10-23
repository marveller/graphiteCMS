$(function() {
	$('.active .section-title').siblings('ul').show();
	$('.section-title').click(function() {
		$('.section-title').not(this).siblings('ul:visible').slideUp();
		$(this).siblings('ul').slideDown();
	});
	setup();
});

console.log(shape.pointsCount);
var canvas,ctx,width,height,
    frameRate = 60,
	pointsCount = shape.pointsCount,
	points = [],
	variation = 5,
	radius = 100,
	swarmDone = false,
	easing = 0.001;
	
function Point(x,y) {
	this.x=x;
	this.y=y;
}

function setup() {
	canvas = document.getElementById("canvas");
	ctx = canvas.getContext('2d');
	width = canvas.width;
	height = canvas.height;
	initPoints();
	setInterval(draw,1000.0/frameRate);
	draw();
}
function initPoints() {
	for(var i=0;i<pointsCount;i++) {
		points[i] = new Point(Math.random()*width,Math.random()*height);
	}
	//centering targets
	for(var i=0;i<shape.paths.length;i++) {
		for(var j=0;j<shape.paths[i].points.length;j++) {
			shape.paths[i].points[j].x+=width/2-shape.width/2;
			shape.paths[i].points[j].y+=height/2-shape.height/2;
		}
	}
}
function updateSwarm() {
	var a = 0;
	easing=Math.min(easing*1.1,1.0);
	var done=true;
	for(var i=0;i<shape.paths.length;i++) {
		for(var j=0;j<shape.paths[i].points.length;j++) {
				
			points[a].x+=  (shape.paths[i].points[j].x-points[a].x)*easing;
			points[a].y+=  (shape.paths[i].points[j].y-points[a].y)*easing	;
			
			if(distance(points[a],shape.paths[i].points[j])>0.1)
			{
				done=false;
			}
			a++;
		}
	}
	if(done==true)
		swarmDone=true;
}
function draw() {
	if(!swarmDone)
		updateSwarm();
	clear();
	//ctx.save();
	ctx.strokeStyle = "#2da7cb";
	for(var i=0;i<points.length;i++) {
		//ctx.fillRect(points[i].x-1,points[i].y-1,2,2);
		ctx.beginPath();
		ctx.moveTo(points[i].x+1,points[i].y-1);
		ctx.lineTo(points[i].x-1,points[i].y+1)
		//ctx.fillRect(points[i].x-1,points[i].y-1,2,2);
		ctx.closePath();
		ctx.stroke();
	}
	//ctx.restore();
}
function clear() {
	//ctx.fillStyle = "rgba(245, 245, 245,1)";
	//ctx.fillRect(0, 0, width, height);
	ctx.clearRect(0,0,width,height);
}
function distance(a,b) {
	return Math.sqrt((b.x-a.x)*(b.x-a.x) + (b.y-a.y)*(b.y-a.y));
}