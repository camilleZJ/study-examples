import React from "react";

function Cat(props) {
  const { mouse } = props;
  return (
    <img
      src="/cat.jpg"
      style={{ position: "absolute", left: mouse.x, top: mouse.y }}
    />
  );
}

class Mouse extends Component {
  constructor(props) {
    super(props);
    this.state = {
      x: 0,
      y: 0,
    };
    this.handleMouseMove = this.handleMouseMove.bind(this);
  }

  handleMouseMove(event) {
    this.setState({
      x: event.clientX,
      y: event.clientY,
    });
  }

  render() {
    return (
      <div onMouseMove={this.handleMouseMove} style={{ height: "100%" }}>
        {/* 
        <p>
          he current mouse position is ({this.state.x}, {this.state.y})
        </p> */}
        {/* <Cat mouse={this.state} /> */}
        {this.props.render(this.state)}
      </div>
    );
  }
}

function MouseTracker() {
  return (
    <div>
      <h1>Move the mouse around!</h1>
      <Mouse render={(mouse) => <Cat mouse={mouse} />} />
    </div>
  );
}

// export default MouseTracker;

function WithMouse(WrappedComponent) {
  return class extends React.PureComponent {
    constructor(props) {
      super(props);
      this.renderChildComponent = this.renderChildComponent.bind(this);
    }

    renderChildComponent(mouse) {
      return <WrappedComponent {...this.props} mouse={mouse} />;
    }

    render() {
      return <Mouse render={this.renderChildComponent} />;
    }
  };
}

export default WithMouse;
