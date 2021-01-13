import React from 'react';
import Schedule from './Schedule.js';
import Search from './Search.js';
import SelectDay from './SelectDay.js';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';

class Schedules extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			page: 1,
			pageSize: 9,
			query: "",
			day: "",
			data: []
		}
		this.handleSearch = this.handleSearch.bind(this);
		this.handleDaySelect = this.handleDaySelect.bind(this);
	}

	componentDidMount() {
		const url = "http://unn-w18001456.newnumyspace.co.uk/Web_Application_Integration/part1/api/sessions"
		fetch(url)
			.then((response) => response.json())
			.then((data) => {
				this.setState({ data: data.data })
			})
			.catch((err) => {
				console.log("Error in fetching data: ", err)
			}
		);
	}

	handlePreviousClick = () => {
		this.setState({ page: this.state.page - 1 })
	}

	handleNextClick = () => {
		this.setState({ page: this.state.page + 1 })
	}

	handleSearch = (e) => {
		this.setState({ page: 1, query: e.target.value })
	}

	searchString = (s) => {
		return s.toLowerCase().includes(this.state.query.toLowerCase())
	}

	searchDetails = (details) => {
		return ((this.searchString(details.name)))
	}

	/**
	 * 
	 * @param {e} ; Value that will be selected to limit data for the day. 
	 */
	handleDaySelect = (e) => {
		this.setState({ page: 1, day: e.target.value })
	}

	selectDetails = (details) => {
		return ((this.state.day === details.dayString) || (this.state.day === ""))
	}

	render() {

		let filteredData = (
			this.state.data
				.filter(this.selectDetails)
				.filter(this.searchDetails)
		)

		let noOfPages = Math.ceil(filteredData.length / this.state.pageSize)
		if (noOfPages === 0) { noOfPages = 1 }

		let disabledPrevious = (this.state.page <= 1)
		let disabledNext = (this.state.page >= noOfPages)

		return (
			<div>
				<h1>Schedule</h1>
				<SelectDay handleDaySelect={this.handleDaySelect} />
				<br />
				<Search query={this.state.query} handleSearch={this.handleSearch} />

				{filteredData
					.slice(((this.state.pageSize * this.state.page) - this.state.pageSize), (this.state.pageSize * this.state.page))
					.map((details, i) => (<Schedule key={i} details={details} />))}
				<button onClick={this.handlePreviousClick} disabled={disabledPrevious}><FontAwesomeIcon icon="angle-left" /></button>
				Page {this.state.page} of {noOfPages}
				<button onClick={this.handleNextClick} disabled={disabledNext}><FontAwesomeIcon icon="angle-right" /></button>
			</div>
		);
	}
}

export default Schedules;