import React from 'react';

class Session extends React.Component {

 render() {

   return (
     <div id="container">
       <li>
           {this.props.details.name}
       </li>
     </div>
   );
 }
}

export default Session;