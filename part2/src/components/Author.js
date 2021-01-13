import React from 'react';
import Content from './Content.js';
import Session from './Session.js';


class Author extends React.Component {

	state = { display: false, data: [], sessions: [] }

	loadContentDetails = () => {
		const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/content?authorId=" + this.props.details.authorId
		fetch(url)
			.then((response) => response.json())
			.then((data) => {
				this.setState({ data: data.data })
			})
			.catch((err) => {
				console.log("something went wrong ", err)
			}
		);
	}

	loadSessionDetails = () => {
		const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/sessions?authorId=" + this.props.details.authorId
		fetch(url)
			.then((response) => response.json())
			.then((data) => {
				this.setState({ sessions: data.data })
			})
			.catch((err) => {
				console.log("Something went wrong ", err)
			}
		);
	}

	handleContentClick = (e) => {
		this.setState({ display: !this.state.display })
		this.loadContentDetails()
		this.loadSessionDetails()
		console.log(this.state.sessions)
		console.log(this.state.data)
	}

	render() {

		let content = "";
		let session = "";

		/**
		 * Displayed session type and duration when required
		 */
		if (this.state.display) {
			content = this.state.data.map((details, i) => (<Content key={i} details={details} />
				)
			);
			session = this.state.sessions.map((session, i) => (<Session key={i} details={session} />)
			);
		}

		return (
			<div className="AuthorTitle">
				<h2 onClick={this.handleContentClick}>{this.props.details.name}</h2>
				{content}
				{session}
			</div>
		);
	}
}


export default Author;