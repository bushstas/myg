import * as React from 'react';
import { hot } from 'react-hot-loader/root';
import { Battle } from './pages/battle';
import './style.scss';

class App extends React.PureComponent {
  static displayName = 'App';

  constructor(props) {
    super(props);
    this.state = {};
  }

  render() {
    return <Battle />;
  }
}

export default hot(App);
