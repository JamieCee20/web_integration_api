import React from 'react';
import './components/FontawesomeIcons';
import DropdownNav from './components/DropdownNav';
import NotFound404 from './components/NotFound404';
import Schedules from './components/Schedules';
import Authors from './components/Authors';
import Admin from './components/Admin.js';
import { BrowserRouter as Router, Switch, Route, NavLink } from "react-router-dom";
import './App.css';

function App() {
	return (
		<Router basename="/Web_Application_Integration/part2">
			<div className="App">
				<nav className="FullNav">
					<ul>
						<li>
							<NavLink exact to="/" activeClassName="selected">Schedule</NavLink>
						</li>
						<li>
							<NavLink exact to="/authors" activeClassName="selected">Authors</NavLink>
						</li>
						<li>
							<NavLink exact to="/admin" activeClassName="selected">Admin</NavLink>
						</li>
					</ul>
				</nav>
				<nav className="DropdownNav">
					<ul>
						<DropdownNav />
					</ul>
				</nav>
				<Switch>
					<Route path="/authors">
						<Authors />
					</Route>
					<Route path="/admin">
						<Admin />
					</Route>
					<Route exact path="/">
						<Schedules />
					</Route>
					<Route path="*">
						<NotFound404 />
					</Route>
				</Switch>
			</div>
		</Router>
	);
}

export default App;
