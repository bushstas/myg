import * as React from 'react';

const store = {};
const subscribers = {};
const map = {};
let uniqueId = 0;

export const addToStore = (name, data) => {
	store[name] = {
		...store[name],
		...data
	};
	if (subscribers[name] instanceof Array) {		
		subscribers[name].forEach(subscriber => {
			subscriber.handleChange({[name]: store[name]});
		});
	}
};

const subscribe = (names, subscriber) => {
	subscriber.uniqueId = uniqueId;
	map[`_${uniqueId}`] = names;
	uniqueId++;
	names.forEach((name) => {
		subscribers[name] = subscribers[name] || [];
		subscribers[name].push(subscriber);
	});
};

const unsubscribe = (subscriber) => {
	const uid = subscriber.uniqueId;
	const names = map[`_${uid}`];
	names.forEach(name => {
		const idx = subscribers[name].indexOf(subscriber);
		if (idx > -1) {
			subscribers[name].splice(idx, 1);
		}
	});
	map[`_${uid}`] = null;
	delete map[`_${uid}`];
};

export class StoreContainer extends React.PureComponent {
	static displayName = 'StoreContainer';

	state = {};
	
	componentDidMount() {
		subscribe(this.props.stored, this);
	}

	componentWillUnmount() {
		unsubscribe(this);	
	}

	isLoaded() {
		const {stored} = this.props;
		for (let i = 0; i < stored.length; i++) {
			if (!this.state[stored[i]]) {
				return false;
			}
		}
		return true;
	}

	handleChange(state) {
		this.setState(state);
	}

	render() {
		const {
			component: Component,
			loader: Loader,
			flatten,
			stored,
			...props
		} = this.props;
		if (this.isLoaded()) {
			return (
				<Component 
					{...props}
					{...(flatten ? this.state[stored[0]] : this.state)}
				/>
			);
	 	}
	 	return Loader ? <Loader /> : null;
	}
}
