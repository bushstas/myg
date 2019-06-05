import * as React from 'react';
import { BattleActions } from 'actions';
import { StoreContainer } from 'utils';
import { BattleField } from './components';
import css from './style.scss';

const actions = new BattleActions();
const stored = ['battle'];

export class Battle extends React.PureComponent {
	static displayName = 'Battle';
	
    componentDidMount() {
        actions.load();
    }

    handleEndTurn = () => {
        actions.endTurn();
    }

    render() {
        return (
            <div className={css.main}>
            	<StoreContainer
            		component={BattleField}
            		stored={stored}
                    onEndTurn={this.handleEndTurn}
                    flatten
            	/>
            </div>
       	);
    }
}
