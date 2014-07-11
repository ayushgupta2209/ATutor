	var data = <?php echo json_encode($rows_hits); ?>;
	console.log(data[0]);
	var margin = {top: 80, right: 80, bottom: 80, left: 80},
		width = 600 - margin.left - margin.right,
		height = 400 - margin.top - margin.bottom;

	var x = d3.scale.ordinal()
		.rangeRoundBands([0, width], .1);

	var y0 = d3.scale.linear().domain([0, 80]).range([height, 0]),
	y1 = d3.scale.linear().domain([0, 80]).range([height, 0]);

	var xAxis = d3.svg.axis()
		.scale(x)
		.orient("bottom");
	
	var svg = d3.select("#bargraph").append("svg")
		.attr("width", width + margin.left + margin.right)
		.attr("height", height + margin.top + margin.bottom)
		.append("g")	
		.attr("class", "graph")
		.attr("transform", "translate(" + margin.left + "," + margin.top + ")");
	x.domain(data.map(function(d) { return d.tool; }));
	y0.domain([0,d3.max(data, function(d) { return +d.Avg_time; })]);
	y1.domain([0,d3.max(data, function(d) { return +d.Avg_time; })]);
	svg.append("g")
		.attr("class", "x axis")
		.attr("transform", "translate(0," + height + ")")
		.call(xAxis);

			// create left yAxis
	var yAxisLeft = d3.svg.axis().scale(y0).ticks(10).orient("left");
	// create right yAxis
	var yAxisRight = d3.svg.axis().scale(y1).ticks(10).orient("right");
	svg.append("g")
		.attr("class", "y axis axisLeft")
		.attr("transform", "translate(0,0)")
		.call(yAxisLeft)
		.append("text")
		.attr("y", 20)
		.attr("dy", "-3em")
		.attr("dx", "7em")
		.style("text-anchor", "end")
		.text("Student's Avg Time");
	
	svg.append("g")
		.attr("class", "y axis axisRight")
		.attr("transform", "translate(" + (width) + ",0)")
		.call(yAxisRight)
		.append("text")
		.attr("y", 20)
		.attr("dy", "-3em")
		.attr("dx", "3em")
		.style("text-anchor", "end")
		.text("Your Avg Time");

	bars = svg.selectAll(".bar").data(data).enter();

	bars.append("rect")
		.attr("class", "bar1")
		.attr("x", function(d) { return x(d.tool); })
		.attr("width", x.rangeBand()/2)
		.attr("y", function(d) { return y0(d.Avg_time); })
		.attr("height", function(d,i,j) { return height - y0(d.Avg_time); }); 

	bars.append("rect")
		.attr("class", "bar2")
		.attr("x", function(d) { return x(d.tool) + x.rangeBand()/2; })
		.attr("width", x.rangeBand() / 2)
		.attr("y", function(d) { return y1(d.Your_avg_time); })
		.attr("height", function(d,i,j) { return height - y1(d.Your_avg_time); });