import React, { Component } from "react";

class LifeCircle extends Component {
  constructor(props) {
    super(props);

    this.state = {
      x: 0,
      y: 0,
      clickEvent: null,
      eventType: null,
    };
    this.handleMouseMove = this.handleMouseMove.bind(this);
    this.handleClick = this.handleClick.bind(this);
  }

  handleMouseMove(event) {
    const clientX = event.clientX;
    console.log(clientX);

    //全是undefined
    this.setState({
      x: clientX,
      y: event.clientY,
    });
  }

  handleClick(event) {
    // event.preventDefault(); // 阻止默认行为
    // event.stopPropagation(); // 阻止冒泡

    event.persist();

    const eventType = event.type;

    // console.log(event);

    //setTimeout输出和react版本有关
    setTimeout(function () {
      console.log("event.type", event.type); // => click
      console.log("eventType", eventType); // => "click"
    }, 0);

    //全是undefined
    this.setState({
      clickEvent: event,
      eventType: event.type,
      //  eventType: eventType,
      x: event.clientX,
      y: event.clientY,
    });

    // console.log(event, event.target, event.currentTarget);
    // console.log("event.__proto__.constructor", event.__proto__.constructor);
    // console.log("nativeEvent", event.nativeEvent);
    // console.log("nativeEvent target", event.nativeEvent.target); // 指向当前元素，即当前元素触发
    // console.log("nativeEvent current target", event.nativeEvent.currentTarget);
  }

  render() {
    const { eventType, clickEvent, x, y } = this.props;

    //全是undefined
    console.log("clickEvent:", clickEvent);
    console.log("eventType:", eventType);

    return (
      <div /*onMouseMove={this.handleMouseMove}*/ onClick={this.handleClick}>
        <p>
          the current position is (x: {x}, y:{y})
        </p>
        {/* <p>clickEvent:{JSON.stringify(clickEvent)}</p> */}
        <p>eventType:{eventType}</p>
      </div>
    );
  }
}

export default LifeCircle;
