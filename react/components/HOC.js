import React from "react";

const HOC = (WrappedComponent, selectData) => {
  class HOC extends React.Component {
    constructor(props) {
      super(props);
      this.state = {
        data: selectData(DataSource, props),
      };
      this.handleChange = this.handleChange.bind(this);
    }

    componentDidMount() {
      DataSource.addEventListener(this.handleChange);
    }
    componentWillUnmount() {
      DataSource.removeEventListener(this.handleChange);
    }

    handleChange() {
      this.setState({
        data: selectData(DataSource, this.props),
      });
    }

    render() {
      const { forwardRef, ...otherProps } = this.props;
      return (
        <WrappedComponent
          {...otherProps}
          ref={forwardRef}
          data={this.state.data}
        />
      );
    }
  }

  function forwardRef(props, ref) {
    return <HOC forwardRef={ref} {...props} />;
  }

  const name = WrappedComponent.displayName || WrappedComponent.name;
  forwardRef.displayName = `wrapped(${name})`;

  return React.forwardRef(forwardRef);
};

export default HOC;
