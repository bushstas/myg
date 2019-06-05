import { addToStore } from 'utils';

export class CommonActions {
    addToStore = (data) => {
    	addToStore(this.name, data);
    }
}
