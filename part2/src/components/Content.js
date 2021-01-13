import React from 'react';

class Content extends React.Component {

 state = {
   display: false,

 }

 handleContentClick = () => {
   this.setState({display:!this.state.display})
 }



 render() {

   let info = "";



   if (this.state.display) {
     info = <div>
              <p>{this.props.details.abstract}</p>
              <p>Award: {this.props.details.award}</p>
            </div>
   }

   return (
     <div id="container" className="ContentTitle">
       <h4 onClick={this.handleContentClick}>{this.props.details.title}</h4>
       {info}
     </div>
   );
 }
}

export default Content;