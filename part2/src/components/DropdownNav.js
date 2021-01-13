import React from 'react';
import { BrowserRouter as Router, Switch, Route, NavLink } from "react-router-dom";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';


class DropdownNav extends React.Component {
	state = { expanded: true }

	toggle = () => {
		this.setState({ expanded: !this.state.expanded });
	}


	render() {
		let nav = ""
		let caret = <FontAwesomeIcon icon="angle-down" />

		if (this.state.expanded) {
			caret = <FontAwesomeIcon icon="angle-up" />
			nav = <div>
				<li>
					<NavLink exact to="/" activeClassName="selected">Schedule</NavLink>
				</li>
				<li>
					<NavLink exact to="/authors" activeClassName="selected">Authors</NavLink>
				</li>
				<li>
					<NavLink exact to="/admin" activeClassName="selected">Admin</NavLink>
				</li>
			</div>
		}

		return (
			<div>
				<button className="DropdownBtn" onClick={this.toggle}>Menu <span>{caret}</span></button>
				{nav}
			</div>
		)
	}
}

export default DropdownNav;