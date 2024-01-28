from flask import Flask, render_template, request, jsonify
import mysql.connector

app = Flask(__name__)

@app.route("/")
def ontology_levels():
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="schcomp"
    )
    mycursor = mydb.cursor()
    mycursor.execute("SELECT ontologyLevel1 FROM quranicsubjects")
    results = mycursor.fetchall()
    links = list(set([result[0] for result in results]))
    return render_template('index.html', links=links)

@app.route('/ontologylevel2')
def ontologylevel2():
    # Get the value of the ontologylevel1 query parameter
    ontologylevel1 = request.args.get('ontologylevel1')
    
    # Connect to the database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="schcomp"
    )
    mycursor = mydb.cursor()
    
    # Fetch the data for ontologylevel2 based on ontologylevel1
    query = "SELECT DISTINCT ontologyLevel2 FROM quranicsubjects WHERE ontologyLevel1 = %s"
    mycursor.execute(query, (ontologylevel1,))
    results = mycursor.fetchall()
    ontologylevel2_values = [result[0] for result in results]
    
    # Return the ontologylevel2 values as a JSON response
    return jsonify(ontologylevel2_values)

@app.route('/ontologylevel3')
def ontologylevel3():
    # Get the values of the ontologylevel1 and ontologylevel2 query parameters
    ontologylevel1 = request.args.get('ontologylevel1')
    ontologylevel2 = request.args.get('ontologylevel2')
    
    # Connect to the database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="schcomp"
    )
    mycursor = mydb.cursor()
    
    # Fetch the data for ontologylevel3 based on ontologylevel1 and ontologylevel2
    query = "SELECT DISTINCT ontologyLevel3 FROM quranicsubjects WHERE ontologyLevel1 = %s AND ontologyLevel2 = %s"
    mycursor.execute(query, (ontologylevel1, ontologylevel2))
    results = mycursor.fetchall()
    ontologylevel3_values = [result[0] for result in results]
    
    # Return the ontologylevel3 values as a JSON response
    return jsonify(ontologylevel3_values)

@app.route('/ontologylevel4')
def ontologylevel4():
    # Get the value of the ontologylevel3 query parameter
    ontologylevel3 = request.args.get('ontologylevel3')
    
    # Connect to the database
    mydb = mysql.connector.connect(
        host="localhost",
        user="root",
        password="",
        database="schcomp"
    )
    mycursor = mydb.cursor()
    
    # Fetch the data for ontologylevel4 based on ontologylevel3
    query = "SELECT DISTINCT ontologyLevel4 FROM quranicsubjects WHERE ontologyLevel3 = %s"
    mycursor.execute(query, (ontologylevel3,))
    results = mycursor.fetchall()
    ontologylevel4_values = [result[0] for result in results]
    
    # Return the ontologylevel4 values as a JSON response
    return jsonify(ontologylevel4_values)

    

if __name__ == "__main__":
    app.run(debug=True)
