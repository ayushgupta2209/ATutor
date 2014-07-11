	var margin = {top: 80, right: 80, bottom: 80, left: 80};
    var width = 600,                        //width
    height = 500,                            //height
    radius = 200,                            //radius
    color = d3.scale.category20();     //builtin range of colors
	var data = <?php echo json_encode($rows_hits); ?>; 
    var vis = d3.select("#piechart")
        .append("svg:svg")              //create the SVG element inside the <body>
        .data([data])                   //associate our data with the document
            .attr("width", width)           //set the width and height of our visualization (these will be attributes of the <svg> tag
            .attr("height", height)
			.attr("transform", "translate(" + margin.left + "," + margin.top + ")")
        .append("svg:g")                //make a group to hold our pie chart
            .attr("transform", "translate(" + radius + "," + radius + ")")    //move the center of the pie chart from 0, 0 to radius, radius

    var arc = d3.svg.arc()              //this will create <path> elements for us using arc data
        .outerRadius(radius - 10)
		.innerRadius(radius-100);
		

    var pie = d3.layout.pie()           //this will create arc data for us given a list of values
        .value(function(d) { return d.total_duration_sec; });    //we must tell it out to access the value of each element in our data array

    var arcs = vis.selectAll("g.slice")     //this selects all <g> elements with class slice (there aren't any yet)
        .data(pie)                          //associate the generated pie data (an array of arcs, each having startAngle, endAngle and value properties) 
        .enter()                            //this will create <g> elements for every "extra" data element that should be associated with a selection. The result is creating a <g> for every object in the data array
            .append("svg:g")                //create a group to hold each slice (we will have a <path> and a <text> element associated with each slice)
                .attr("class", "slice");    //allow us to style things in the slices (like text)

        arcs.append("svg:path")
                .attr("fill", function(d, i) { return color(i); } ) //set the color for each slice to be chosen from the color function defined above
                .attr("d", arc)
				.append("title")
        .text(function(d, i) { return data[i].content_id+'\n'+data[i].total_duration; });                                    //this creates the actual SVG path using the associated data (pie) with the arc drawing function