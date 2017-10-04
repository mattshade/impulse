"use strict";
var datastorm = datastorm || {};

datastorm.network = (function(){
  var my = {};

  var elements = {};

  var width, height;
  var active = true;

  var config = {};

  var nodes = [], links = [];

  var force = d3.layout.force()
    .charge(-1500)
    .linkDistance(50)
    .linkStrength(function(d) {
      return d.strength;
    })
    .gravity(0.01)
    .friction(0.1)
    .on('tick', render);

  // var timer;

  var ctx = datastorm.canvas.ctx;

  var colorScale = d3.scale.category20();



  function init() {
    elements.svg = d3.select('svg');
    d3.select('canvas')
      .attr('width', config.width)
      .attr('height', config.height);

    force.size([config.width, config.height]);
  }

  function updateNetwork() {
    nodes = _.clone(datastorm.network.sim.getUsers());
    links = _.clone(datastorm.network.sim.getLinks());

    force.nodes(nodes)
      .links(links)
      .start();
  }

  function render() {
    ctx.globalAlpha = 1;

    ctx.fillStyle = "rgba(255, 255, 255, 0.4)";
    ctx.fillRect(0, 0, config.width, config.height);



    ctx.globalAlpha = 0.5;

    // Links
    ctx.strokeStyle = 'rgba(200, 200, 255, 0.7)';
    ctx.lineWidth = 0.5;

    _.each(links, function(d) {
      datastorm.canvas.drawLine(d.source.x, d.source.y, d.target.x, d.target.y);
    });

    // // Nodes
    // console.log('render', nodes.length);
    // ctx.fillStyle = 'rgba(200,200,255,0.5)';
    _.each(nodes, function(d) {
      // console.log(d);
      ctx.fillStyle = "#efefef";
      datastorm.canvas.drawCircle(d.x, d.y, 5);
    });
  }


  my.init = function(conf) {
    _.assign(config, conf);

    init();

    setInterval(render, 100);

    // updateNetwork();
  };

  my.stop = function() {
    // clearInterval(timer);

    datastorm.network.sim.stop();
  };

  my.networkUpdate = function() {
    updateNetwork();
  }

  return my;
}());


datastorm.network.sim = (function(){
  var my = {};

  var timer;
  var users = [], /*messages = [],*/ links = [];
  var messageId = -1;

  var config = {
    addUserProbability: 0.1,
    addMessageProbability: 0.6,
    addFriendProbability: 1,
    repeatMessageProbability: 0.9,
    repeatMessageTimespan: 4000, // timespan (milliseconds) in which a message can be repeated
    maxUsers: 40
  };


  /*-----
  HELPERS
  -----*/
  function shouldDo(probability) {
    // Decide, based on the given probability, whether event should occur
    return Math.random() < probability;
  }

  // function randomColor() {
//     var color = d3.rgb(Math.random() * 255, Math.random() * 255, Math.random() * 255);
//     color = color.toString();
//     console.log(color);
//     return color;
//   }

  /*----
  EVENTS
  ----*/
  function addUser() {
    if(!shouldDo(config.addUserProbability))
      return;

    if(users.length > config.maxUsers)
      return;

    var newUser = {
      id: users.length,
      chatty: 0.01 + 0.02 * Math.random(), // how often user messages
      friendly: 0.8 + 0.2 * Math.random(), // how likely user is to friend another user
      interesting: Math.random(), // how interesting the user's messages are
      subject: Math.floor(Math.random() * 10), // the user's interest area,
      friends: [],
      messages: [],
      x: 0.5 * config.width + 10 * Math.random(), //TODO
      y: 0.5 * config.height + 10 * Math.random()//TODO
    };

    // console.log(newUser);
    users.push(newUser);
    datastorm.network.networkUpdate();
  }


  

  
  function addFriend() {
    if(!shouldDo(config.addFriendProbability))
      return;

    _.each(users, function(thisUser) {
      _.each(users, function(otherUser) {
        if(thisUser === otherUser)
          return;

        var probability = otherUser.chatty * thisUser.friendly * otherUser.interesting;

        if(thisUser.subject !== otherUser.subject)
          probability *= 0.002;

        if(!shouldDo(probability))
          return;

        var alreadyFriends = _.find(thisUser.friends, function(friend) {
          return friend.id === otherUser.id;
        });
        if(alreadyFriends)
          return;

        // I don't think we have to do this reverse check...
        alreadyFriends = _.find(otherUser.friends, function(friend) {
          return friend.id === thisUser.id;
        });
        if(alreadyFriends)
          return;

        var strength = 0.05;

        links.push({
          source: thisUser.id,
          target: otherUser.id,
          strength: strength
        });

        thisUser.friends.push(otherUser);
        otherUser.friends.push(thisUser);

        datastorm.network.networkUpdate();


      });
    });
  }


  function update() {
    addUser();
    addFriend();
  }

  my.init = function(conf) {
    _.assign(config, conf);
  };

  my.start = function() {
    timer = setInterval(update, 100);
  }

  my.stop = function() {
    clearInterval(timer);
  }

  my.getUsers = function() {
    return users;
  }

  my.getMessages = function() {
    return messages;
  }

  my.getLinks = function() {
    return links;
  }

  return my;
}());


(function(){

  var dswrapper = d3.select('.dswrapper');
  var width = dswrapper.node().clientWidth;
  var height = dswrapper.node().clientHeight;

  datastorm.network.sim.init({
    width: width,
    height: height
  });
  datastorm.network.init({
    width: width,
    height: height
  });

  datastorm.network.sim.start();

})();